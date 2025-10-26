<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\CoffeeChat;
use App\Models\Channel;
use App\Models\User;
use App\Models\WorkspaceField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ChannelSeeder::class,
            PageSeeder::class,
            SeoMetaSeeder::class,
            WorkspaceFieldSeeder::class,
            TeamFinderDemoSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        if (Post::count() === 0) {
            Post::factory(5)->for($admin, 'author')->create();

            User::factory(5)->create()->each(function (User $user): void {
                Post::factory(2)->draft()->for($user, 'author')->create();
            });
        }

        if (CoffeeChat::count() === 0) {
            $workspaceFields = WorkspaceField::formFields();

            CoffeeChat::factory()
                ->count(8)
                ->completed()
                ->for($admin)
                ->create()
                ->each(function (CoffeeChat $chat) use ($workspaceFields): void {
                    $limit = rand(1, min(3, Channel::count()));
                    $chat->channels()->sync(
                        Channel::query()->inRandomOrder()->limit($limit)->pluck('id')->all()
                    );

                    $chat->forceFill([
                        'extras' => $this->fakerExtras($workspaceFields),
                    ])->save();
                });

            CoffeeChat::factory()
                ->count(4)
                ->planned()
                ->for(User::factory())
                ->create()
                ->each(function (CoffeeChat $chat) use ($workspaceFields): void {
                    $chat->forceFill([
                        'extras' => $this->fakerExtras($workspaceFields),
                    ])->save();
                });
        }
    }

    protected function fakerExtras($fields): array
    {
        $faker = fake();

        return collect($fields)->mapWithKeys(function ($field) use ($faker) {
            switch ($field->type) {
                case 'number':
                    return [$field->key => $faker->numberBetween(1, 5)];
                case 'date':
                    return [$field->key => $faker->date()];
                case 'datetime':
                    return [$field->key => $faker->dateTime()->format('Y-m-d H:i:s')];
                case 'boolean':
                    return [$field->key => $faker->boolean()];
                case 'multiselect':
                    $values = collect($field->options ?? [])->pluck('value')->filter()->all();
                    $selected = $faker->randomElements($values, rand(0, min(3, count($values))));
                    return [$field->key => array_values($selected)];
                case 'select':
                    $values = collect($field->options ?? [])->pluck('value')->filter()->all();
                    return [$field->key => $faker->randomElement($values ?: [null])];
                case 'textarea':
                    return [$field->key => $faker->paragraph()];
                default:
                    return [$field->key => $faker->words(rand(1, 3), true)];
            }
        })->filter(function ($value) {
            return $value !== null && $value !== [];
        })->all();
    }
}
