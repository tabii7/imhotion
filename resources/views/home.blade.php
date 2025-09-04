@extends('layouts.app')

@section('content')
<!-- v0 design imported; design-only changes. Laravel data (like $categories) still available in Blade. -->
<div class="min-h-screen bg-slate-900 text-white">
  <div class="text-center pt-12 pb-8">
    <p class="text-sm font-medium tracking-wider text-slate-300 uppercase">PRICES</p>
  </div>

  <div class="max-w-4xl mx-auto px-6 mb-16">
    <div class="text-center mb-12">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Price per day</h1>
      <p class="text-slate-400 text-lg">For small projects or an extra day</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 max-w-2xl mx-auto">
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl">
        <h3 class="text-xl font-semibold mb-4 text-white">Weekday</h3>
        <div class="flex items-center justify-between bg-white rounded-full p-2">
          <span class="text-slate-900 font-semibold px-4">€549/day</span>
          <button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </button>
        </div>
      </div>

      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl">
        <h3 class="text-xl font-semibold mb-4 text-white">Weekend</h3>
        <div class="flex items-center justify-between bg-white rounded-full p-2">
          <span class="text-slate-900 font-semibold px-4">€649/day</span>
          <button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-6 mb-16">
    <div class="text-center mb-12">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Our Packs</h2>
      <p class="text-slate-400 text-lg">Save and get extra days</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
        <div class="absolute top-4 right-4"><span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€2,595</span></div>
        <div class="mb-6"><h3 class="text-3xl font-bold mb-2 text-white">5 Days</h3><p class="text-slate-400 mb-1">€519/day</p><p class="text-slate-500 text-sm">Valid for 1 year</p></div>
        <div class="space-y-3 mb-8">
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"><path d="M20 6 9 17l-5-5"></path></svg><span class="text-white">5% Discount</span></div>
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg><span class="text-slate-500">Gift Box</span></div>
        </div>
        <button class="w-full bg-white text-slate-900 rounded-full py-2 font-semibold">Get 5 Days <svg class="inline-block w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button>
      </div>

      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
        <div class="absolute top-4 right-4"><span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€4,990</span></div>
        <div class="mb-6"><h3 class="text-3xl font-bold mb-2 text-white">10 Days</h3><p class="text-slate-400 mb-1">€499/day</p><p class="text-slate-500 text-sm">Valid for 3 years</p></div>
        <div class="space-y-3 mb-8">
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"><path d="M20 6 9 17l-5-5"></path></svg><span class="text-white">10% Discount</span></div>
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"><path d="M20 6 9 17l-5-5"></path></svg><span class="text-white">Gift Box</span></div>
        </div>
        <button class="w-full bg-white text-slate-900 rounded-full py-2 font-semibold">Get 10 Days <svg class="inline-block w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button>
      </div>

      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl relative">
        <div class="absolute top-4 right-4"><span class="bg-slate-700 text-white px-3 py-1 rounded-full text-sm">€9,380</span></div>
        <div class="mb-6"><h3 class="text-3xl font-bold mb-2 text-white">20 Days</h3><p class="text-slate-400 mb-1">€469/day</p><p class="text-slate-500 text-sm">Valid for 5 years</p></div>
        <div class="space-y-3 mb-8">
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"><path d="M20 6 9 17l-5-5"></path></svg><span class="text-white">15% Discount</span></div>
          <div class="flex items-center gap-3"><svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor"><path d="M20 6 9 17l-5-5"></path></svg><span class="text-white">Gift Box</span></div>
        </div>
        <button class="w-full bg-white text-slate-900 rounded-full py-2 font-semibold">Get 20 Days <svg class="inline-block w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button>
      </div>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-6 pb-16">
    <div class="text-center mb-12"><h2 class="text-4xl md:text-5xl font-bold mb-4">Addons</h2><p class="text-slate-400 text-lg">Enhance your project with more options</p></div>
    <div class="grid md:grid-cols-2 gap-6">
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl"><h3 class="text-xl font-semibold mb-4 text-white">Extended Day</h3><div class="flex items-center justify-between bg-white rounded-full p-2"><span class="text-slate-900 font-semibold px-4">+€79/hour</span><button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button></div></div>
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl"><h3 class="text-xl font-semibold mb-4 text-white">Project Files</h3><div class="flex items-center justify-between bg-white rounded-full p-2"><span class="text-slate-900 font-semibold px-4">€99/year</span><button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button></div></div>
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl"><h3 class="text-xl font-semibold mb-4 text-white">Camera Package</h3><div class="flex items-center justify-between bg-white rounded-full p-2"><span class="text-slate-900 font-semibold px-4">€249/day</span><button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button></div></div>
      <div class="text-card-foreground flex flex-col gap-6 border shadow-sm bg-slate-800 border-slate-700 p-6 rounded-2xl"><h3 class="text-xl font-semibold mb-4 text-white">Website Content</h3><div class="flex items-center justify-between bg-white rounded-full p-2"><span class="text-slate-900 font-semibold px-4">€349/project</span><button class="inline-flex items-center justify-center rounded-full bg-slate-900 hover:bg-slate-800 text-white h-8 px-3"><svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg></button></div></div>
    </div>
  </div>
</div>

@endsection
    @foreach($categories as $category)
