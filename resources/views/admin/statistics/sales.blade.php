@extends('admin.layout')

@section('title', 'Statistiques des ventes')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">STATISTIQUES</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Analyse des ventes</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres de période -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.statistics.sales') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Hier</option>
                    <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="last_week" {{ $period == 'last_week' ? 'selected' : '' }}>Semaine dernière</option>
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
    
    <!-- Résumé des ventes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistique Commandes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Commandes</h3>
                <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $sales->sum('count') }}</div>
            <p class="text-gray-500 text-sm">Total des commandes</p>
        </div>
        
        <!-- Statistique Revenus -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Revenus</h3>
                <div class="rounded-full bg-green-100 p-3 text-green-500">
                    <i class="fas fa-money-bill text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($sales->sum('total'), 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Revenus totaux</p>
        </div>
        
        <!-- Statistique Commande moyenne -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-amber-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Panier moyen</h3>
                <div class="rounded-full bg-amber-100 p-3 text-amber-500">
                    <i class="fas fa-shopping-basket text-xl"></i>
                </div>
            </div>
            @php
                $averageOrder = $sales->sum('count') > 0 ? $sales->sum('total') / $sales->sum('count') : 0;
            @endphp
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($averageOrder, 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Valeur moyenne des commandes</p>
        </div>
        
        <!-- Statistique clients -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Période</h3>
                <div class="rounded-full bg-purple-100 p-3 text-purple-500">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-accent mb-2">
                {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
            </div>
            <p class="text-gray-500 text-sm">{{ $endDate->diffInDays($startDate) + 1 }} jours</p>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique évolution des ventes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Évolution des ventes</h3>
            </div>
            <div class="p-4">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Graphique des méthodes de paiement -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Méthodes de paiement</h3>
            </div>
            <div class="p-4">
                <canvas id="paymentMethodsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tableau détaillé -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-accent text-white p-4">
            <h3 class="font-bold text-lg">Détail des ventes par jour</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de commandes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panier moyen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $day->count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ number_format($day->total, 2) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($day->count > 0 ? $day->total / $day->count : 0, 2) }} MAD
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Aucune donnée disponible pour cette période
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Tableau méthodes de paiement -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-accent text-white p-4">
            <h3 class="font-bold text-lg">Répartition par méthode de paiement</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode de paiement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de commandes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% des revenus</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalRevenue = $salesByPaymentMethod->sum('total');
                    @endphp
                    @forelse($salesByPaymentMethod as $method)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                @if($method->methode_paiement == 'carte')
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                                        Carte bancaire
                                    </span>
                                @elseif($method->methode_paiement == 'livraison')
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                        Paiement à la livraison
                                    </span>
                                @elseif($method->methode_paiement == 'virement')
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-university mr-2 text-purple-500"></i>
                                        Virement bancaire
                                    </span>
                                @else
                                    {{ ucfirst($method->methode_paiement) }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $method->count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                {{ number_format($method->total, 2) }} MAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($totalRevenue > 0 ? ($method->total / $totalRevenue) * 100 : 0, 2) }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
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
        
        const salesData = @json($sales);
        const salesLabels = salesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('fr-FR');
        });
        const salesValues = salesData.map(item => item.total);
        const orderCounts = salesData.map(item => item.count);
        
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [
                    {
                        label: 'Revenus (MAD)',
                        data: salesValues,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Nombre de commandes',
                        data: orderCounts,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenus (MAD)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                        title: {
                            display: true,
                            text: 'Nombre de commandes'
                        }
                    }
                }
            }
        });
        
        const paymentData = @json($salesByPaymentMethod);
        const paymentLabels = paymentData.map(item => {
            switch(item.methode_paiement) {
                case 'carte': return 'Carte bancaire';
                case 'livraison': return 'Paiement à la livraison';
                case 'virement': return 'Virement bancaire';
                default: return item.methode_paiement;
            }
        });
        const paymentValues = paymentData.map(item => item.total);
        const paymentColors = [
            'rgba(59, 130, 246, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(139, 92, 246, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(239, 68, 68, 0.7)'
        ];
        
        const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentValues,
                    backgroundColor: paymentColors,
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                
                                return `${label}: ${value.toFixed(2)} MAD (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection