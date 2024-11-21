import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

Echo.channel("admin-notifications").listen(".voucher.status.changed", (e) => {
    console.log("Voucher status changed:", e.voucher);
    // Tambahkan notifikasi ke ikon
    addNotificationToIcon(e.voucher);
});

function addNotificationToIcon(voucher) {
    const notificationIcon = document.getElementById("notification-icon");
    const notificationCount =
        parseInt(notificationIcon.getAttribute("data-count")) || 0;
    notificationIcon.setAttribute("data-count", notificationCount + 1);
    document.querySelector("#notification-icon .badge").innerText =
        notificationCount + 1;

    // Tambahkan notifikasi ke dropdown
    const notificationList = document.getElementById("notification-list");
    const notificationItem = document.createElement("li");
    notificationItem.classList.add("notification-item");
    notificationItem.innerText = `Voucher ${voucher.code} status changed to ${voucher.status}`;
    notificationList.appendChild(notificationItem);
}
