<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DailyWorkEvent } from '@/lib/event-utils';
import { Info } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';
import { MonthlyWorkProps } from './AddTargetsModal.vue';
import { ref } from 'vue';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Separator } from '@/components/ui/separator';
import { SharedData } from '@/types';
import { Switch } from '@/components/ui/switch';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger, } from "@/components/ui/tooltip";
import { useDebounceFn } from '@vueuse/core';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import dayGridPlugin from '@fullcalendar/daygrid';
import FullCalendar from '@fullcalendar/vue3';
import idLocale from '@fullcalendar/core/locales/id';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import LkhForm, { DailyWorkField, FormOption } from './LkhForm.vue';
import timeGridPlugin from '@fullcalendar/timegrid';
import type { CalendarOptions, EventApi, DateSelectArg, EventClickArg, EventChangeArg, DatesSetArg, ViewApi } from '@fullcalendar/core';

const page = usePage<SharedData>();
const locale = ref<string>(page.props.app.locale || page.props.app.fallback_locale);
const dailyWorks = ref<DailyWorkEvent[]>(page.props.daily_works as DailyWorkEvent[]);
const currentEvents = ref<EventApi[]>([]);
const showLkhForm = ref(false);
const currentRow = ref<DailyWorkField | null>(null);
const selectedDate = ref<DateSelectArg>();
const currentCalendarView = ref<ViewApi>();
const formOption = ref<FormOption>({
	monthly_works: page.props.monthly_works as MonthlyWorkProps[],
});
const isFetchingEvents = ref(false);
const calendarOptions = ref<CalendarOptions>({
	themeSystem: 'standard',
	plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
	headerToolbar: {
		left: 'prev,next today dayGridMonth listWeek',
		right: 'title',
		// right: 'dayGridMonth,timeGridWeek,timeGridDay',
	},
	titleFormat: {
		year: 'numeric',
		month: 'short',
		day: 'numeric',
	},
	eventTimeFormat: {
		hour: '2-digit',
		minute: '2-digit',
		meridiem: false
	},
	dayHeaderFormat: { weekday: 'short' },
	buttonText: {
		today: 'Hari ini',
		month: 'Bulan',
		week: 'Minggu',
		day: 'Hari',
		list: 'Agenda'
	},
	customButtons: {
		myCustomButton: {
			text: 'Click me!',
			click: function () {
				alert('clicked the custom button!');
			}
		}
	},
	dayHeaderClassNames: ({ date }: any) => [0, 6].includes(date.getDay()) ? ['text-destructive', 'dark:text-destructive'] : [],
	dayCellDidMount: ({ date, el }: any) => {
		if ([0, 6].includes(date.getDay())) {
			el.classList.add('text-destructive', 'dark:text-destructive')
		}
	},
	initialView: 'dayGridMonth',
	initialEvents: dailyWorks.value,
	events: [],
	editable: true,
	selectable: true,
	selectMirror: true,
	dayMaxEvents: true,
	weekends: true,
	locale: locale.value,
	locales: [idLocale],
	datesSet: handleDateRangeChange,
	eventsSet: handleEvents,
	select: handleDateSelect,
	eventClick: handleEventClick,
	eventChange: handleEventChange,
})

function handleWeekendsToggle() {
	calendarOptions.value.weekends = !calendarOptions.value.weekends
}

function handleDateSelect(selectInfo: DateSelectArg) {
	handleAddLckh();
	selectedDate.value = selectInfo;

	// const title = prompt('Please enter a new title for your event')
	// const calendarApi = selectInfo.view.calendar
	// calendarApi.unselect()

	// if (title) {
	// 	calendarApi.addEvent({
	// 		id: createEventId(),
	// 		title,
	// 		start: selectInfo.startStr,
	// 		end: selectInfo.endStr,
	// 		allDay: selectInfo.allDay,
	// 	})
	// }
}

function handleEvents(events: EventApi[]) {
	currentEvents.value = events;
}

function handleDateRangeChange(events: DatesSetArg) {
	const prev = currentCalendarView.value;
	currentCalendarView.value = events.view;

	if (!prev) {
		console.log('📅 FULL CALENDAR — first load');
	} else {
		debouncedFetch();
	}
}

function handleEventClick(clickInfo: EventClickArg) {
	const { evidence_link, file, monthly_work_id, output, quantity, unit } = clickInfo.event.extendedProps;
	currentRow.value = {
		id: clickInfo.event.id,
		monthly_work_id,
		title: clickInfo.event.title,
		quantity,
		unit,
		output,
		start: clickInfo.event.startStr,
		end: clickInfo.event.endStr,
		evidence_link,
		file,
	}

	showLkhForm.value = true;
	// if (confirm(`Are you sure you want to delete the event '${clickInfo.event.title}'?`)) {
	// 	clickInfo.event.remove();
	// }
}

function handleEventChange(events: EventChangeArg) {
	console.log("removed", events);
}

function handleAddLckh() {
	showLkhForm.value = true;
	currentRow.value = null;
}

function handleOpenChange(close: boolean) {
	showLkhForm.value = close;
}

const debouncedFetch = useDebounceFn(() => {
	fetchEventData()
}, 500)

async function fetchEventData() {
	isFetchingEvents.value = true;
	try {
		const startDate = currentCalendarView.value?.currentStart;
		const response = await axios.get(route('logbook.lkh.events'), {
			params: {
				month: (startDate?.getMonth() || 0) + 1,
				year: startDate?.getFullYear() || new Date().getFullYear(),
			},
		});

		const result = response.data;
		const data = result?.data;

		if (data) {
			calendarOptions.value.events = data.daily_works;
			formOption.value.monthly_works = data.monthly_works;
		}
	} catch (err) {
		console.error(err);
	} finally {
		isFetchingEvents.value = false;
	}
}

</script>

<template>
	<LkhForm :open="showLkhForm" :onOpenChange="handleOpenChange" :currentRow :selectedDate :formOption :title="trans('LKH Tanggal: ' + (selectedDate?.startStr || currentRow?.start))" :description="trans('Isi formulir di bawah ini untuk menambah laporan kerja harian.')" />
	<div class="flex flex-col lg:flex-row gap-4">
		<div class="w-full md:w-3/5 lg:w-1/3 xl:w-1/4 2xl:w-1/5">
			<div className="flex items-center justify-between py-2">
				<div className="flex gap-2">
					<h2 className="text-xl font-bold">Petunjuk</h2>
				</div>
				<Button variant="outline" class="rounded-md size-8">
					<Info class="size-4" />
				</Button>
			</div>
			<ScrollArea type="auto" orientation="vertical" class="bg-background w-full min-w-40 px-1 py-2 md:block">
				<div class="mb-3">
					<ul class="list-disc space-y-2 text-muted-foreground">
						<li>
							<span class="font-semibold">Membuat LKH baru:</span>
							<ol class="list-decimal text-sm">
								<li>{{ trans('Klik pada tanggal.') }}</li>
								<li>{{ trans('Isi formulir kegiatan. ') }}</li>
								<li>{{ trans('Klik simpan.') }}</li>
							</ol>
						</li>
						<li>
							<span class="font-bold">Mengubah LKH:</span>
							<ol class="list-decimal text-sm">
								<li>{{ trans('Klik pada kegiatan.') }}</li>
								<li>{{ trans('Ubah data pada formulir kegiatan.') }}</li>
								<li>{{ trans('Klik simpan perubahan.') }}</li>
							</ol>
						</li>
						<li>
							<span class="font-bold text-destructive">Menghapus LKH:</span>
							<ol class="list-decimal text-sm">
								<li>{{ trans('Klik pada kegiatan.') }}</li>
								<li>{{ trans('Klik tombol hapus (icon trash).') }}</li>
							</ol>
						</li>
					</ul>
				</div>

				<Separator class="my-3" />

				<div className="flex items-center justify-between py-2 mb-2">
					<div className="flex gap-2">
						<h2 className="text-xl font-bold">Aksi Lainnya</h2>
					</div>
				</div>

				<div class="flex items-center space-x-2">
					<Switch id="airplane-mode" :modelValue="!calendarOptions.weekends" @update:modelValue="handleWeekendsToggle" />
					<Label for="airplane-mode">Sembunyikan weekends</Label>
				</div>

				<!-- <Separator class="my-3" />
				<h2>Kegiatan Tersimpan ({{ currentEvents.length }})</h2>
				<ul class="list-disc text-sm">
					<li v-for="event in currentEvents" :key="event.id">
						<i>{{ event.title }}</i>
					</li>
				</ul> -->
			</ScrollArea>
		</div>

		<Separator class="my-2 lg:hidden" />

		<div class="flex-1 overflow-y-hidden h-screen p-2 border bg-secondary shadow-lg rounded-lg">
			<FullCalendar class="p-0 w-full" :options="calendarOptions">
				<template #eventContent="{ timeText, event }">
					<TooltipProvider>
						<Tooltip>
							<TooltipTrigger as-child>
								<i>{{ event.title }}</i>
							</TooltipTrigger>
							<TooltipContent>
								<p>{{ event.startStr }} | {{ event.title }}</p>
							</TooltipContent>
						</Tooltip>
					</TooltipProvider>
				</template>
			</FullCalendar>
		</div>
	</div>
</template>
