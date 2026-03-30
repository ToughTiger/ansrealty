<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Browser Push Notifications --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        🔔 Browser Push Notifications
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Get instant browser notifications for important updates
                    </p>
                </div>
                <div>
                    <button 
                        id="enable-push-btn"
                        onclick="enablePushNotifications()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Enable Push Notifications
                    </button>
                    <button 
                        id="disable-push-btn"
                        onclick="disablePushNotifications()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 hidden"
                    >
                        Disable Push Notifications
                    </button>
                </div>
            </div>
            
            <div id="notification-status" class="mt-4 p-4 rounded-lg hidden">
                <p class="text-sm"></p>
            </div>
        </div>

        {{-- Notification Preferences --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                📧 Notification Preferences
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">New Lead Assigned</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Get notified when a new lead is assigned to you</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Task Overdue</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Alerts for overdue tasks</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Lead Status Changed</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">When lead status is updated</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">New Opportunity</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">When a new opportunity is created</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Email Notifications</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Receive notifications via email</p>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </div>
            </div>
        </div>

        {{-- Test Notification --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                🧪 Test Notifications
            </h3>
            <button 
                onclick="sendTestNotification()"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
            >
                Send Test Notification
            </button>
        </div>
    </div>

    @push('scripts')
    <script>
        // Check notification permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            checkNotificationPermission();
        });

        function checkNotificationPermission() {
            if (!("Notification" in window)) {
                showStatus('Browser does not support notifications', 'error');
                return;
            }

            const enableBtn = document.getElementById('enable-push-btn');
            const disableBtn = document.getElementById('disable-push-btn');

            if (Notification.permission === 'granted') {
                enableBtn.classList.add('hidden');
                disableBtn.classList.remove('hidden');
                showStatus('✅ Push notifications are enabled', 'success');
            } else if (Notification.permission === 'denied') {
                showStatus('❌ Push notifications are blocked. Please enable in browser settings.', 'error');
                enableBtn.disabled = true;
            } else {
                showStatus('ℹ️ Click "Enable Push Notifications" to receive alerts', 'info');
            }
        }

        async function enablePushNotifications() {
            if (!("Notification" in window)) {
                alert("This browser does not support notifications");
                return;
            }

            try {
                const permission = await Notification.requestPermission();
                
                if (permission === "granted") {
                    showStatus('✅ Push notifications enabled successfully!', 'success');
                    document.getElementById('enable-push-btn').classList.add('hidden');
                    document.getElementById('disable-push-btn').classList.remove('hidden');
                    
                    // Send test notification
                    new Notification("Notifications Enabled! 🎉", {
                        body: "You will now receive real-time updates",
                        icon: "/favicon.ico",
                        badge: "/favicon.ico"
                    });
                } else {
                    showStatus('❌ Notification permission denied', 'error');
                }
            } catch (error) {
                console.error('Error enabling notifications:', error);
                showStatus('❌ Error enabling notifications', 'error');
            }
        }

        function disablePushNotifications() {
            showStatus('ℹ️ To disable notifications, please use your browser settings', 'info');
        }

        function sendTestNotification() {
            if (Notification.permission !== 'granted') {
                alert('Please enable push notifications first');
                return;
            }

            new Notification("Test Notification 🧪", {
                body: "This is a test notification from ANS Realty CRM",
                icon: "/favicon.ico",
                badge: "/favicon.ico",
                tag: "test-notification",
                requireInteraction: false
            });

            showStatus('✅ Test notification sent!', 'success');
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('notification-status');
            const statusText = statusDiv.querySelector('p');
            
            statusDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'bg-blue-100', 'text-green-800', 'text-red-800', 'text-blue-800');
            
            if (type === 'success') {
                statusDiv.classList.add('bg-green-100', 'dark:bg-green-900', 'text-green-800', 'dark:text-green-200');
            } else if (type === 'error') {
                statusDiv.classList.add('bg-red-100', 'dark:bg-red-900', 'text-red-800', 'dark:text-red-200');
            } else {
                statusDiv.classList.add('bg-blue-100', 'dark:bg-blue-900', 'text-blue-800', 'dark:text-blue-200');
            }
            
            statusText.textContent = message;
        }
    </script>
    @endpush
</x-filament-panels::page>
