@extends('admin.layout')

@section('title', 'Statistiques des utilisateurs')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">STATISTIQUES</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Analyse des clients</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres de période -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.statistics.users') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="all_time" {{ $period == 'all_time' ? 'selected' : '' }}>Depuis le début</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Mois dernier</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Cette année</option>
                    <option value="last_year" {{ $period == 'last_year' ? 'selected' : '' }}>Année dernière</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                </select>
            </div>
            
            <div id="custom-date-container" class="flex-1 {{ $period != 'custom' ? 'hidden' : '' }}">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                        <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                        <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                </div>
            </div>
            
            <div>
                <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                    Appliquer
                </button>
            </div>
        </form>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Nouveaux utilisateurs -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Nouveaux clients</h3>
                <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $newUsers }}</div>
            <p class="text-gray-500 text-sm">
                @if($period == 'this_month')
                    Ce mois-ci
                @elseif($period == 'last_month')
                    Le mois dernier
                @elseif($period == 'this_year')
                    Cette année
                @elseif($period == 'last_year')
                    L'année dernière
                @elseif($period == 'custom')
                    Période sélectionnée
                @else
                    Au total
                @endif
            </p>
        </div>
        
        <!-- Clients réguliers -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Clients actifs</h3>
                <div class="rounded-full bg-green-100 p-3 text-green-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $topCustomers->count() }}</div>
            <p class="text-gray-500 text-sm">Clients avec commandes</p>
        </div>
        
        <!-- Commandes par client -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-amber-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Commandes/client</h3>
                <div class="rounded-full bg-amber-100 p-3 text-amber-500">
                    <i class="fas fa-shopping-basket text-xl"></i>
                </div>
            </div>
            @php
                $avgOrders = $topCustomers->count() > 0 ? $topCustomers->sum('total_orders') / $topCustomers->count() : 0;
            @endphp
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($avgOrders, 1) }}</div>
            <p class="text-gray-500 text-sm">En moyenne</p>
        </div>
        
        <!-- Dépense moyenne -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Dépense moyenne</h3>
                <div class="rounded-full bg-purple-100 p-3 text-purple-500">
                    <i class="fas fa-money-bill text-xl"></i>
                </div>
            </div>
            @php
                $avgSpent = $topCustomers->count() > 0 ? $topCustomers->sum('total_spent') / $topCustomers->count() : 0;
            @endphp
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($avgSpent, 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Par client actif</p>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique évolution utilisateurs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Évolution des inscriptions</h3>
            </div>
            <div class="p-4">
                <canvas id="usersChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Graphique dépenses clients -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Répartition des dépenses clients</h3>
            </div>
            <div class="p-4">
                <canvas id="spendingChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Meilleurs clients -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-accent text-white p-4 flex justify-between items-center">
            <h3 class="font-bold text-lg">Top clients</h3>
            <a href="{{ route('admin.users.index') }}" class="text-white hover:text-secondary transition-colors duration-200">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commandes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dépenses totales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panier moyen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topCustomers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-accent">
                                {{ $customer->name }} {{ $customer->prenom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $customer->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $customer->total_orders }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ number_format($customer->total_spent, 2) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($customer->total_orders > 0 ? $customer->total_spent / $customer->total_orders : 0, 2) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.users.show', $customer->id) }}" class="text-primary hover:text-primary-dark mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $customer->id) }}" class="text-primary hover:text-primary-dark">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune donnée disponible pour cette période
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Évolution des inscriptions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-accent text-white p-4">
            <h3 class="font-bold text-lg">Évolution des inscriptions par mois</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mois</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nouveaux clients</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Évolution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $previousCount = null;
                    @endphp
                    @forelse($userRegistrationByMonth as $month)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $month->count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($previousCount !== null)
                                    @php
                                        $evolution = $previousCount > 0 ? (($month->count - $previousCount) / $previousCount) * 100 : 0;
                                    @endphp
                                    <span class="inline-flex items-center {{ $evolution >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        <i class="fas fa-{{ $evolution >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                        {{ number_format(abs($evolution), 2) }}%
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @php
                            $previousCount = $month->count;
                        @endphp
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Aucune donnée disponible pour cette période
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le sélecteur de dates
        flatpickr(".datepicker", {
            dateFormat: "Y-m-d",
        });
        
        // Afficher/masquer les champs de date personnalisée
        const periodSelect = document.getElementById('period');
        const customDateContainer = document.getElementById('custom-date-container');
        
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateContainer.classList.remove('hidden');
            } else {
                customDateContainer.classList.add('hidden');
            }
        });
        
        // Graphique évolution des inscriptions
        const registrationData = @json($userRegistrationByMonth);
        const registrationLabels = registrationData.map(item => {
            const [year, month] = item.month.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
        });
        const registrationCounts = registrationData.map(item => item.count);
        
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: registrationLabels,
                datasets: [{
                    label: 'Nouveaux clients',
                    data: registrationCounts,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Graphique répartition des dépenses
        const spendingData = @json($topCustomers);
        
        // Regrouper les dépenses par catégories
        const spendingCategories = {
            '0-500': 0,
            '501-1000': 0,
            '1001-2000': 0,
            '2001-5000': 0,
            '5001+': 0
        };
        
        spendingData.forEach(customer => {
            const spent = customer.total_spent;
            
            if (spent <= 500) {
                spendingCategories['0-500']++;
            } else if (spent <= 1000) {
                spendingCategories['501-1000']++;
            } else if (spent <= 2000) {
                spendingCategories['1001-2000']++;
            } else if (spent <= 5000) {
                spendingCategories['2001-5000']++;
            } else {
                spendingCategories['5001+']++;
            }
        });
        
        const spendingLabels = Object.keys(spendingCategories);
        const spendingCounts = Object.values(spendingCategories);
        
        const spendingCtx = document.getElementById('spendingChart').getContext('2d');
        new Chart(spendingCtx, {
            type: 'bar',
            data: {
                labels: spendingLabels,
                datasets: [{
                    label: 'Nombre de clients',
                    data: spendingCounts,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(139, 92, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.raw} client(s)`;
                            },
                            title: function(context) {
                                return `Dépenses: ${context[0].label} MAD`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        },
                        ticks: {
                            precision: 0
                        },
                        title: {
                            display: true,
                            text: 'Nombre de clients'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Montant dépensé (MAD)'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection