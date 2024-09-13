import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import duration from 'dayjs/plugin/duration';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(utc);
dayjs.extend(timezone);
dayjs.extend(duration);
dayjs.extend(relativeTime);


export function appendTableData(data: string, elemtId: string) {
    const tableBody = document.getElementById(elemtId);
    if (tableBody) {
        tableBody.innerHTML = data;
    }
}

export function formatDuration(created: string): string {

    const createdDate = dayjs.tz(created, timeZone);

    const currentDate = dayjs().tz(timeZone);
    // Calculate the difference between currentDate and createdDate in human-readable form
    const timeDifference = currentDate.diff(createdDate);

    return createdDate.from(currentDate);
}

export function calculateProgress(created: string, ends: string): number {
    try {

        const format = 'YYYY-MM-DD HH:mm:ss';

        const createdDate = dayjs.tz(created, format, timeZone);
        const endsDate = dayjs.tz(ends, format, timeZone);
        const currentDate = dayjs().tz(timeZone);

        // Validate the date strings to make sure they are valid
        if (!createdDate.isValid() || !endsDate.isValid()) {
            return "NA";
        }

        // Calculate total time between creation and end
        const totalTime = endsDate.diff(createdDate); // Difference in milliseconds

        // Prevent division by zero or invalid total time
        if (totalTime <= 0) {
            return "NA";
        }

        // Calculate elapsed time from creation to current date
        const elapsedTime = currentDate.diff(createdDate);

        // Calculate progress as a percentage
        let progress = (elapsedTime / totalTime) * 100;

        // Ensure progress is a valid number and doesn't exceed 100% or go below 0%
        progress = Math.min(100, Math.max(0, isNaN(progress) ? "NA" : progress));
        return Number(progress.toFixed(2));
    } catch (e) {
        return "NA";
    }
}

export function showLoader(loaderId = 'loader') {
    document.getElementById(loaderId).style.display = "block";
}

export function hideLoader(loaderId = 'loader') {
    document.getElementById(loaderId).style.display = "none";
}
