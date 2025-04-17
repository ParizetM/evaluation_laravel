<x-modal name="{{ $name ?? 'contacts-modal' }}" focusable maxWidth="5xl">
    <div class="p-2">
        <a x-on:click="$dispatch('close')">
            <x-icons.close class="float-right mb-1 icons" size="1.5" unfocus />
        </a>

        <div class="p-6">
            <table class="min-w-full bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <!-- En-tête -->
                <thead class="bg-linear-to-r from-gray-200 to-gray-50 dark:from-gray-700 dark:to-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poste
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Téléphone</th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Données existantes -->
                    @if ($contacts->isEmpty())
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"
                                colspan="4">
                                Aucun contact n'a été ajouté pour cette société.
                            </td>
                        </tr>

                    @else
                    @foreach ($contacts as $contact)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $contact->prenom }} {{ $contact->nom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ $contact->fonction }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                <a href="mailto:{{ $contact->email }}"
                                    class="text-blue-500 hover:text-blue-700 dark:text-blue-300">
                                    {{ $contact->email }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if ($contact->telephone_fixe)
                                    <div><strong>Fixe :</strong> <a href="tel:{{ $contact->telephone_fixe }}"
                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-300">
                                        {{ $contact->telephone_fixe }}
                                    </a></div>
                                @endif
                                @if ($contact->telephone_portable)
                                    <div><strong>Portable :</strong> <a href="tel:{{ $contact->telephone_portable }}"
                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-300">
                                        {{ $contact->telephone_portable }}
                                    </a></div>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    <!-- Ligne pour ajouter un contact -->
                    <tr class="overflow-hidden border-t-0">
                        <form method="POST" class="w-full" id="form-{{ $name ?? 'contacts-modal' }}">
                            @csrf
                            <input type="hidden" name="etablissement_id" value="{{ $contact->etablissement->id }}">
                            <td>
                                <input type="text" name="nom" class="w-full btn-contact" placeholder="Nom"
                                    required style="padding: 1.75rem 0.1rem;">
                            </td>
                            <td>
                                <input type="text" name="fonction" class="w-full btn-contact" placeholder="Poste"
                                    style="padding: 1.75rem 0.1rem;">
                            </td>
                            <td>
                                <input type="email" name="email" class="w-full btn-contact" placeholder="Email"
                                    required style="padding: 1.75rem 0.1rem;">
                            </td>
                            <td class="flex w-full items-center overflow-hidden">
                                <div>
                                    <input type="tel" name="telephone_fixe" class="w-full btn-contact"
                                        placeholder="Téléphone fixe">
                                    <input type="tel" name="telephone_portable" class="w-full btn-contact"
                                        placeholder="Téléphone portable">
                                </div>
                                    <button type="submit" class="btn-select-square h-24 ml-1 border-0 scale-105" title="Ajouter un contact"
                                    >
                                        +
                                    </button></div>
                            </td>
                        </form>
                    </tr>
                    <script>
                        document.getElementById('form-{{ $name ?? 'contacts-modal' }}').addEventListener('submit', function(event) {
                            event.preventDefault();

                            let formData = new FormData(this);

                            fetch('{{ route('societes.contacts.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    showFlashMessageFromJs('Contact ajouté avec succès', duree = 2000, type = 'success')
                                } else {
                                    showFlashMessageFromJs('Erreur lors de l\'ajout du contact', duree = 2000, type = 'error')
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showFlashMessageFromJs('Erreur lors de l\'ajout du contact', duree = 2000, type = 'error')
                            });
                        });
                    </script>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-modal>
