<div>
    @if ($agent->position == 'L1')
        <div class="grid grid-cols-2 gap-6">
            <section>
                <h1 class="text-xl font-bold mb-5">CCO Email (L1)</h1>

                <div class="!grid grid-cols-3 gap-4">
                    @foreach ($ccoEmail as $row)
                        <div class="border p-4 shadow-md rounded-md">
                            <h1 class="text-lg font-bold">{{ $months[$loop->iteration] }}</h1>
                            <p class="text-sm">{{ round($row) }}%</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section>
                <h1 class="text-xl font-bold mb-5">CCO Telepon (L1)</h1>

                <div class="!grid grid-cols-3 gap-4">
                    @foreach ($ccoPhone as $row)
                        <div class="border p-4 shadow-md rounded-md">
                            <h1 class="text-lg font-bold">{{ $months[$loop->iteration] }}</h1>
                            <p class="text-sm">{{ round($row) }}%</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    @else
        <section>
            <h1 class="text-xl font-bold mb-5">CHO (L2)</h1>

            <div class="!grid grid-cols-3 gap-4">
                @foreach ($cho as $row)
                    <div class="border p-4 shadow-md rounded-md">
                        <h1 class="text-lg font-bold">{{ $months[$loop->iteration] }}</h1>
                        <p class="text-sm">{{ round($row) }}%</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
