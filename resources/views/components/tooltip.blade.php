@props(['slot_item', 'slot_tooltip', 'position' => 'auto', 'class' => ''])

<div x-data="tooltip('{{ $position }}')" class="relative inline-block {{ $class }}"
     @mouseenter="calculatePosition($event); show = true"
     @mouseleave="hideTooltip()">
    {{ $slot_item }}

    <template x-teleport="body">
        <div x-ref="tooltip" x-show="show" x-transition :style="style" class="z-[9999]"
             @mouseenter="enterTooltip" @mouseleave="leaveTooltip">
            <div class="p-2 rounded-lg border shadow-md bg-white border-gray-200 text-gray-800
                        dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                {{ $slot_tooltip }}
            </div>
        </div>
    </template>
</div>
