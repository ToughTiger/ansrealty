// Browser Push Notifications Handler
class PushNotificationManager {
    constructor() {
        this.checkSupport();
        this.initializeListeners();
    }

    checkSupport() {
        if (!('Notification' in window)) {
            console.warn('Browser does not support notifications');
            return false;
        }
        return true;
    }

    async requestPermission() {
        if (Notification.permission === 'granted') {
            return true;
        }

        if (Notification.permission !== 'denied') {
            const permission = await Notification.requestPermission();
            return permission === 'granted';
        }

        return false;
    }

    sendNotification(title, options = {}) {
        if (Notification.permission !== 'granted') {
            console.warn('Notification permission not granted');
            return;
        }

        const defaultOptions = {
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            vibrate: [200, 100, 200],
            requireInteraction: false,
            ...options
        };

        const notification = new Notification(title, defaultOptions);

        // Auto close after 5 seconds
        setTimeout(() => {
            notification.close();
        }, 5000);

        // Click handler
        notification.onclick = function(event) {
            event.preventDefault();
            window.focus();
            if (options.url) {
                window.location.href = options.url;
            }
            notification.close();
        };

        return notification;
    }

    initializeListeners() {
        // Listen for custom events from Livewire/Filament
        window.addEventListener('notification', (event) => {
            const { title, body, type, url } = event.detail;
            
            this.sendNotification(title, {
                body: body,
                url: url,
                tag: `notification-${Date.now()}`,
                icon: this.getIconForType(type)
            });
        });

        // Listen for database notifications polling
        window.addEventListener('database-notifications-updated', (event) => {
            const notifications = event.detail;
            
            if (notifications && notifications.length > 0) {
                const latest = notifications[0];
                this.sendNotification(
                    this.getNotificationTitle(latest),
                    {
                        body: this.getNotificationBody(latest),
                        url: '/admin',
                        tag: `db-notification-${latest.id}`
                    }
                );
            }
        });
    }

    getIconForType(type) {
        const icons = {
            'success': '/icons/success.png',
            'warning': '/icons/warning.png',
            'error': '/icons/error.png',
            'info': '/icons/info.png',
            'lead': '/icons/lead.png',
            'task': '/icons/task.png',
            'opportunity': '/icons/opportunity.png'
        };
        return icons[type] || '/favicon.ico';
    }

    getNotificationTitle(notification) {
        const data = notification.data || {};
        
        if (data.lead_name) {
            return `🎯 ${data.message || 'New Lead'}`;
        }
        if (data.task_title) {
            return `⏰ ${data.message || 'Task Update'}`;
        }
        if (data.customer_name) {
            return `💰 ${data.message || 'New Opportunity'}`;
        }
        
        return notification.message || 'New Notification';
    }

    getNotificationBody(notification) {
        const data = notification.data || {};
        
        if (data.lead_name) {
            return `Lead: ${data.lead_name} - ${data.priority || ''} priority`;
        }
        if (data.task_title) {
            return `Task: ${data.task_title}`;
        }
        if (data.customer_name) {
            return `Customer: ${data.customer_name}`;
        }
        
        return 'Click to view details';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.pushNotificationManager = new PushNotificationManager();
});

// Helper function to send custom notifications
window.sendBrowserNotification = function(title, body, type = 'info', url = null) {
    if (window.pushNotificationManager) {
        window.pushNotificationManager.sendNotification(title, {
            body: body,
            url: url,
            tag: `custom-${Date.now()}`
        });
    }
};
