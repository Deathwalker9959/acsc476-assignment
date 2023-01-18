const storageManager = {
    set: (key, value) => {
        if (typeof value === "object") {
            value = JSON.stringify(value);
        }
        localStorage.setItem(key, value);
    },
    get: (key) => {
        let value = localStorage.getItem(key);
        try {
            value = JSON.parse(value);
        } catch (e) {
            value = value;
        }
        return value;
    },
    remove: (key) => {
        localStorage.removeItem(key);
    },
    clear: () => {
        localStorage.clear();
    },
    append: (key, value) => {
        let currentValue = storageManager.get(key);
        if (!Array.isArray(currentValue)) {
            console.error(`Cannot append to non-array value stored under key: ${key}`);
            return;
        }
        currentValue.push(value);
        storageManager.set(key, currentValue);
    }
};

export default storageManager;
