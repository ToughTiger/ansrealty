<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Calendar Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    📅 Task Calendar
                </h2>
                <div class="flex gap-2">
                    <a href="{{ route('filament.admin.resources.tasks.create') }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        + New Task
                    </a>
                </div>
            </div>
        </div>

        {{-- Calendar Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div id="calendar"></div>
        </div>

        {{-- Legend --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Color Legend</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Completed</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Overdue</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-orange-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">High Priority</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-blue-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Medium Priority</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gray-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Low Priority</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var tasks = @json($this->getViewData()['tasks']);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: tasks,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.url) {
                        window.location.href = info.event.url;
                    }
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    const props = info.event.extendedProps;
                    info.el.title = `${info.event.title}\n` +
                                   `Priority: ${props.priority}\n` +
                                   `Status: ${props.status}\n` +
                                   `Assigned: ${props.assigned_to || 'Unassigned'}`;
                },
                height: 'auto',
                nowIndicator: true,
                navLinks: true,
                businessHours: true,
                editable: false,
                selectable: true,
            });

            calendar.render();
        });
    </script>
    @endpush
</x-filament-panels::page>
