export function appendTableData(data: string, elemtId: string) {
    const tableBody = document.getElementById(elemtId);
    if (tableBody) {
        tableBody.innerHTML = data;
    }
}

export function formatDuration(created: string): string {
    const createdUTCDate = new Date(created + 'Z');
    const currentUTCDate = new Date();

    const timeDifference = currentUTCDate.getTime() - createdUTCDate.getTime();
    const secondsDifference = Math.floor(timeDifference / 1000);

    // Define time intervals in seconds
    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
    };

    // Iterate through intervals to find the appropriate time format
    for (const [interval, seconds] of Object.entries(intervals)) {
        const intervalCount = Math.floor(secondsDifference / seconds);
        if (intervalCount >= 1) {
            return `${intervalCount} ${interval}${intervalCount > 1 ? 's' : ''} ago`;
        }
    }

    return 'Just now';
}

export function calculateProgress(created: string, ends: string): number {
    const createdDate = new Date(created);
    const endsDate = new Date(ends);
    const currentDate = new Date();

    // Convert current date time to UTC
    const currentUTCDate = new Date(
        Date.UTC(
            currentDate.getUTCFullYear(),
            currentDate.getUTCMonth(),
            currentDate.getUTCDate(),
            currentDate.getUTCHours(),
            currentDate.getUTCMinutes(),
            currentDate.getUTCSeconds(),
            currentDate.getUTCMilliseconds()
        )
    );

    const totalTime = endsDate.getTime() - createdDate.getTime();
    const elapsedTime = currentUTCDate.getTime() - createdDate.getTime();

    let progress = (elapsedTime / totalTime) * 100;

    // Ensure progress doesn't exceed 100% or go below 0%
    progress = Math.min(100, Math.max(0, progress));

    return progress;
}

export function showLoader(loaderId = 'loader') {
    document.getElementById(loaderId).style.display = "block";
}

export function hideLoader(loaderId = 'loader') {
    document.getElementById(loaderId).style.display = "none";
}
