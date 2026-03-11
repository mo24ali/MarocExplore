<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 dark:text-white">Créer un itinéraire</h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-indigo-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl p-8 border border-indigo-100 dark:border-slate-700">
                <form id="create-itinerary-form" action="{{ route('iterinary.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Titre</label>
                            <input id="title" name="title" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="Road trip Atlas" />
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Catégorie</label>
                            <input id="category" name="category" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="montagne" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Durée</label>
                            <input id="duration" name="duration" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="5 jours" />
                        </div>
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">URL image</label>
                            <input id="image" name="image" type="url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="https://example.com/image.jpg" />
                        </div>
                    </div>

                    <section class="space-y-3">
                        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Destinations (minimum 2)</h3>
                        <div id="destinations-container" class="space-y-4"></div>
                        <button type="button" id="add-destination" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Ajouter une destination</button>
                    </section>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">Créer l'itinéraire</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const destinationsContainer = document.getElementById('destinations-container');
        const addBtn = document.getElementById('add-destination');
        let destinationIndex = 0;

        function createDestinationBlock(index) {
            const wrapper = document.createElement('div');
            wrapper.className = 'border border-indigo-200 dark:border-slate-700 rounded-lg p-4 bg-indigo-50 dark:bg-slate-900';
            wrapper.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom</label>
                        <input name="destinations[${index}][name]" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-slate-600 dark:text-white" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lieu de logement</label>
                        <input name="destinations[${index}][lieu_logement]" required class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-slate-600 dark:text-white" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Image (URL)</label>
                        <input name="destinations[${index}][image]" type="url" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-slate-600 dark:text-white" placeholder="https://example.com/destination.jpg" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Lieux (CSV, optionnel)</label>
                        <input name="destinations[${index}][places]" placeholder='Médina, Kasbah' class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-slate-600 dark:text-white" />
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Activités (CSV, optionnel)</label>
                    <input name="destinations[${index}][activities]" placeholder='Randonnée, Shopping' class="mt-1 block w-full rounded-md border-gray-300 dark:bg-slate-900 dark:border-slate-600 dark:text-white" />
                </div>
                <button type="button" class="mt-4 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="this.closest('div').remove();">Supprimer la destination</button>
            `;
            destinationsContainer.appendChild(wrapper);
        }

        addBtn.addEventListener('click', () => {
            createDestinationBlock(destinationIndex);
            destinationIndex++;
        });

        createDestinationBlock(destinationIndex++);
        createDestinationBlock(destinationIndex++);
    </script>
</x-app-layout>
