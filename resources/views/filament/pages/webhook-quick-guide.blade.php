<div class="space-y-6 p-6">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-900">🔗 Webhook Integration Guide</h2>
        <p class="text-gray-600 mt-2">Connect your lead sources in minutes</p>
    </div>

    <!-- Meta (Facebook) -->
    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <h3 class="text-xl font-bold text-blue-900">Meta (Facebook) Lead Ads</h3>
        </div>
        
        <div class="space-y-3 text-sm">
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">📍 Webhook URL:</p>
                <code class="block bg-gray-100 p-2 rounded text-blue-600 font-mono">
                    {{ url('/api/webhooks/meta-leads') }}
                </code>
                <button onclick="navigator.clipboard.writeText('{{ url('/api/webhooks/meta-leads') }}')" 
                        class="mt-2 text-xs text-blue-600 hover:text-blue-800">
                    📋 Copy to clipboard
                </button>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">🔑 Verify Token:</p>
                <code class="block bg-gray-100 p-2 rounded text-blue-600 font-mono">ansrealty_webhook_token</code>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-3">📋 Setup Steps:</p>
                <ol class="list-decimal list-inside space-y-1 text-gray-600">
                    <li>Go to Facebook Business Manager → Settings → Webhooks</li>
                    <li>Click "Configure Webhooks" for your Page</li>
                    <li>Paste the webhook URL above</li>
                    <li>Enter verify token: <code class="bg-gray-100 px-1">ansrealty_webhook_token</code></li>
                    <li>Subscribe to <code class="bg-gray-100 px-1">leadgen</code> event</li>
                    <li>Click "Verify and Save"</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Google Ads -->
    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
            <h3 class="text-xl font-bold text-green-900">Google Ads Lead Forms</h3>
        </div>
        
        <div class="space-y-3 text-sm">
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">📍 Webhook URL:</p>
                <code class="block bg-gray-100 p-2 rounded text-green-600 font-mono">
                    {{ url('/api/webhooks/google-leads') }}
                </code>
                <button onclick="navigator.clipboard.writeText('{{ url('/api/webhooks/google-leads') }}')" 
                        class="mt-2 text-xs text-green-600 hover:text-green-800">
                    📋 Copy to clipboard
                </button>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-3">📋 Setup via Zapier:</p>
                <ol class="list-decimal list-inside space-y-1 text-gray-600">
                    <li>Create new Zap: <strong>Google Ads Lead Form → Webhooks</strong></li>
                    <li>Choose "POST" method</li>
                    <li>Paste webhook URL above</li>
                    <li>Map fields: <code class="bg-gray-100 px-1">name</code>, <code class="bg-gray-100 px-1">phone</code>, <code class="bg-gray-100 px-1">email</code></li>
                    <li>Test and activate!</li>
                </ol>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">📝 Required Fields:</p>
                <div class="space-y-1 text-gray-600">
                    <div><code class="bg-gray-100 px-2 py-1 rounded">name</code> (required)</div>
                    <div><code class="bg-gray-100 px-2 py-1 rounded">phone</code> (required)</div>
                    <div><code class="bg-gray-100 px-2 py-1 rounded">email</code> (optional)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic API -->
    <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
        <div class="flex items-center gap-3 mb-4">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            <h3 class="text-xl font-bold text-purple-900">Generic Lead API</h3>
        </div>
        
        <div class="space-y-3 text-sm">
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">📍 API Endpoint:</p>
                <code class="block bg-gray-100 p-2 rounded text-purple-600 font-mono">
                    POST {{ url('/api/leads') }}
                </code>
                <button onclick="navigator.clipboard.writeText('{{ url('/api/leads') }}')" 
                        class="mt-2 text-xs text-purple-600 hover:text-purple-800">
                    📋 Copy to clipboard
                </button>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">📝 Sample Request:</p>
                <pre class="bg-gray-900 text-green-400 p-3 rounded text-xs overflow-x-auto"><code>{
  "full_name": "John Doe",
  "mobile": "9876543210",
  "email": "john@example.com",
  "budget_min": 5000000,
  "budget_max": 10000000,
  "preferred_locations": ["Andheri", "Bandra"],
  "property_types": ["Flat", "Villa"],
  "purchase_intent": "Buy",
  "priority": "Hot",
  "lead_source": "Website Form"
}</code></pre>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-3">✅ Use Cases:</p>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    <li>Website contact forms</li>
                    <li>Mobile apps</li>
                    <li>WhatsApp chatbots</li>
                    <li>Property portals (99acres, MagicBricks)</li>
                    <li>Third-party integrations</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Test Instructions -->
    <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-yellow-900 mb-4">🧪 Testing Your Webhooks</h3>
        
        <div class="space-y-3 text-sm">
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">Using cURL:</p>
                <pre class="bg-gray-900 text-green-400 p-3 rounded text-xs overflow-x-auto"><code>curl -X POST {{ url('/api/leads') }} \
  -H "Content-Type: application/json" \
  -d '{"full_name":"Test User","mobile":"9876543210","email":"test@example.com"}'</code></pre>
            </div>
            
            <div class="bg-white rounded p-4">
                <p class="font-semibold text-gray-700 mb-2">Using Postman:</p>
                <ol class="list-decimal list-inside space-y-1 text-gray-600">
                    <li>Create new POST request</li>
                    <li>URL: <code class="bg-gray-100 px-1">{{ url('/api/leads') }}</code></li>
                    <li>Body → raw → JSON</li>
                    <li>Paste sample JSON above</li>
                    <li>Send!</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Monitoring -->
    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">📊 Monitoring & Logs</h3>
        
        <div class="bg-white rounded p-4 text-sm">
            <p class="text-gray-600 mb-3">View webhook activity in Laravel logs:</p>
            <code class="block bg-gray-900 text-green-400 p-3 rounded">
                tail -f storage/logs/laravel.log | grep "Webhook"
            </code>
            
            <p class="text-gray-600 mt-4 mb-2">All webhook calls are logged with:</p>
            <ul class="list-disc list-inside space-y-1 text-gray-600 ml-2">
                <li>Request payload</li>
                <li>Response status</li>
                <li>Created lead ID</li>
                <li>Error messages (if any)</li>
            </ul>
        </div>
    </div>
</div>
