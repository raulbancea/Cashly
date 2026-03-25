<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Welcome back, {{ auth()->user()->name }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-sm font-semibold text-gray-800">
                    {{ auth()->user()->company_name ?? 'No company set' }}
                </p>
                <p class="text-xs text-gray-500">
                    Plan: {{ auth()->user()->plan ?? 'Free' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        Welcome to Cashly
                    </h3>
                    <p class="mt-2 text-gray-600">
                        Cashly is your financial management workspace. From here, you will track income,
                        expenses, categories, and reports in a clean SaaS-ready structure.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="p-6 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                    <p class="text-sm text-gray-500">Total Balance</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">0.00 RON</h3>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                    <p class="text-sm text-gray-500">Total Income</p>
                    <h3 class="mt-2 text-3xl font-bold text-green-600">0.00 RON</h3>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                    <p class="text-sm text-gray-500">Total Expenses</p>
                    <h3 class="mt-2 text-3xl font-bold text-red-600">0.00 RON</h3>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                    <p class="text-sm text-gray-500">Transactions</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">0</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="p-6 bg-white border border-gray-100 shadow-sm lg:col-span-2 sm:rounded-2xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Company Profile Overview
                    </h3>

                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                        <div>
                            <p class="text-gray-500">Company Name</p>
                            <p class="font-medium text-gray-900">
                                {{ auth()->user()->company_name ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">VAT / CUI</p>
                            <p class="font-medium text-gray-900">
                                {{ auth()->user()->company_vat ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Phone</p>
                            <p class="font-medium text-gray-900">
                                {{ auth()->user()->phone ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Currency</p>
                            <p class="font-medium text-gray-900">
                                {{ auth()->user()->currency ?? 'RON' }}
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-gray-500">Address</p>
                            <p class="font-medium text-gray-900">
                                {{ auth()->user()->address ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Quick Actions
                    </h3>

                    <div class="space-y-3">
                        <button class="w-full px-4 py-3 font-medium text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">
                            Add Income
                        </button>

                        <button class="w-full px-4 py-3 font-medium text-white transition bg-red-500 rounded-xl hover:bg-red-600">
                            Add Expense
                        </button>

                        <button class="w-full px-4 py-3 font-medium text-white transition bg-gray-900 rounded-xl hover:bg-gray-800">
                            View Reports
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm sm:rounded-2xl">
                <div class="p-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-900">
                        Recent Activity
                    </h3>
                    <p class="text-gray-500">
                        No transactions available yet. In the next step, we will create the transactions module
                        and show real data here.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
