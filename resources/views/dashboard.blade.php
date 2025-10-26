<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Workspace Overview
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-500 mt-1">Head to your workspace to review or log coffee chats.</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('workspace.coffee-chats.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Log coffee chat</a>
                        <a href="{{ route('workspace.coffee-chats.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Go to workspace</a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Quick tips</h4>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Use “Log coffee chat” to capture a new conversation immediately.</li>
                        <li>Switch a chat to “Follow-up required” to surface next actions.</li>
                        <li>Track outreach channels to understand what converts best.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
