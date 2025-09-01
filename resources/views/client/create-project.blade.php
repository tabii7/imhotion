@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 text-slate-100">
  <h1 class="text-3xl font-semibold mb-6">Request a New Project</h1>

  <form method="POST" action="{{ route('client.projects.store') }}" class="bg-slate-800 rounded-xl p-6 max-w-3xl">
    @csrf

    <label class="block text-sm text-slate-300 mb-1">Project Name</label>
    <input name="name" required class="w-full mb-4 rounded-lg bg-slate-900 border border-slate-700 px-3 py-2" placeholder="e.g. Brand Identity Redesign" value="{{ old('name') }}"/>

    <label class="block text-sm text-slate-300 mb-1">Topic (optional)</label>
    <input name="topic" class="w-full mb-4 rounded-lg bg-slate-900 border border-slate-700 px-3 py-2" placeholder="e.g. Design System" value="{{ old('topic') }}"/>

    <label class="block text-sm text-slate-300 mb-1">Desired Delivery Date (optional)</label>
    <input type="date" name="desired_date" class="w-full mb-4 rounded-lg bg-slate-900 border border-slate-700 px-3 py-2" value="{{ old('desired_date') }}"/>

    <label class="block text-sm text-slate-300 mb-1">Brief (optional)</label>
    <textarea name="brief" rows="6" class="w-full mb-6 rounded-lg bg-slate-900 border border-slate-700 px-3 py-2" placeholder="Tell us the goal, scope, references...">{{ old('brief') }}</textarea>

    <div class="flex gap-3">
      <a href="{{ url('/client') }}" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600">Cancel</a>
      <button class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500">Submit request</button>
    </div>
  </form>
</div>
@endsection
