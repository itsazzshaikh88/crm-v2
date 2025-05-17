async function useFetch(url, method = "GET", data = null, headers = {}) {
    try {
        // ✅ Basic validation
        if (!url || typeof url !== "string") {
            throw new Error("Invalid URL provided.");
        }

        const validMethods = ["GET", "POST", "PUT", "DELETE"];
        if (!validMethods.includes(method.toUpperCase())) {
            throw new Error(`Invalid HTTP method: ${method}. Allowed: ${validMethods.join(", ")}`);
        }

        // ✅ Default headers (can be overridden)
        const defaultHeaders = {
            "Content-Type": "application/json",
            ...headers,
        };

        // ✅ Fetch options
        const options = {
            method: method.toUpperCase(),
            headers: defaultHeaders,
            signal: new AbortController().signal, // For timeout handling
        };

        if (data && (method === "POST" || method === "PUT")) {
            options.body = JSON.stringify(data);
        }

        // ✅ Timeout handling (5000ms = 5 sec)
        const timeout = setTimeout(() => {
            options.signal.abort();
        }, 5000);

        // ✅ Fetch API call
        const response = await fetch(url, options);
        clearTimeout(timeout);

        // ✅ Handle non-200 responses
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
        }

        // ✅ Parse JSON response
        const result = await response.json();
        return { success: true, data: result };

    } catch (error) {
        console.error("Fetch Error:", error.message);
        return { success: false, message: error.message };
    }
}
