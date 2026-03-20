
<?php /** Flight filter form component */ ?>

<form class="flight-filter-form">

    <div class="flex flex-col sm:flex-row rounded-none overflow-clip border border-black">

        <div class="bg-white px-3 pt-2.5 pb-1.5 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
          <label for="name" class="block text-xs font-medium text-gray-900">From</label>
          <input
              hx-get="/api/airports"
              hx-trigger="keypress"
              hx-target="#from-result"
              id="name"
              type="text"
              name="name"
              placeholder="ZHR - Zurich"
              class="block w-full text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
        </div>
        <div id="#from-result"></div>

        <div class="bg-white px-3 pt-2.5 pb-1.5 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
          <label for="name" class="block text-xs font-medium text-gray-900">To</label>
          <input id="name" type="text" name="name" placeholder="MAD - Madrid" class="block w-full text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
        </div>

    </div>
</form>
