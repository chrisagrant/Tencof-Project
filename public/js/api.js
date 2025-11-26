/**
 * API Helper untuk komunikasi dengan Laravel Backend
 */

const API_BASE_URL = '/api';

// Setup default headers
function getHeaders() {
    return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
    };
}

// Generic API call function
async function apiCall(endpoint, method = 'GET', data = null) {
    try {
        const options = {
            method: method,
            headers: getHeaders()
        };

        if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
            options.body = JSON.stringify(data);
        }

        const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        showToast(`Error: ${error.message}`, 'error');
        throw error;
    }
}

// Bahan Baku API
const BahanBakuAPI = {
    getAll: () => apiCall('/bahan-baku'),
    get: (id) => apiCall(`/bahan-baku/${id}`),
    create: (data) => apiCall('/bahan-baku', 'POST', data),
    update: (id, data) => apiCall(`/bahan-baku/${id}`, 'PUT', data),
    delete: (id) => apiCall(`/bahan-baku/${id}`, 'DELETE')
};

// Satuan API
const SatuanAPI = {
    getAll: () => apiCall('/satuan'),
    get: (id) => apiCall(`/satuan/${id}`),
    create: (data) => apiCall('/satuan', 'POST', data),
    update: (id, data) => apiCall(`/satuan/${id}`, 'PUT', data),
    delete: (id) => apiCall(`/satuan/${id}`, 'DELETE')
};

// Supplier API
const SupplierAPI = {
    getAll: () => apiCall('/supplier'),
    get: (id) => apiCall(`/supplier/${id}`),
    create: (data) => apiCall('/supplier', 'POST', data),
    update: (id, data) => apiCall(`/supplier/${id}`, 'PUT', data),
    delete: (id) => apiCall(`/supplier/${id}`, 'DELETE')
};

// Stock API
const StockAPI = {
    getAll: () => apiCall('/stock'),
    get: (id) => apiCall(`/stock/${id}`),
    create: (data) => apiCall('/stock', 'POST', data),
    update: (id, data) => apiCall(`/stock/${id}`, 'PUT', data),
    delete: (id) => apiCall(`/stock/${id}`, 'DELETE')
};

// Stock History API
const StockHistoryAPI = {
    getAll: () => apiCall('/stock-history'),
    get: (id) => apiCall(`/stock-history/${id}`),
    create: (data) => apiCall('/stock-history', 'POST', data),
    update: (id, data) => apiCall(`/stock-history/${id}`, 'PUT', data),
    delete: (id) => apiCall(`/stock-history/${id}`, 'DELETE')
};

// Auth API (untuk logout)
const AuthAPI = {
    logout: () => apiCall('/logout', 'POST')
};

// Caching untuk mengurangi API calls
const apiCache = {
    satuans: null,
    suppliers: null,
    bahanBakus: null,
    stocks: null,
    stockHistories: null,
    
    async getSatuans() {
        if (!this.satuans) {
            const response = await SatuanAPI.getAll();
            this.satuans = response.data || [];
        }
        return this.satuans;
    },
    
    async getSuppliers() {
        if (!this.suppliers) {
            const response = await SupplierAPI.getAll();
            this.suppliers = response.data || [];
        }
        return this.suppliers;
    },
    
    async getBahanBakus() {
        if (!this.bahanBakus) {
            const response = await BahanBakuAPI.getAll();
            this.bahanBakus = response.data || [];
        }
        return this.bahanBakus;
    },
    
    async getStocks() {
        if (!this.stocks) {
            const response = await StockAPI.getAll();
            this.stocks = response.data || [];
        }
        return this.stocks;
    },
    
    async getStockHistories() {
        if (!this.stockHistories) {
            const response = await StockHistoryAPI.getAll();
            this.stockHistories = response.data || [];
        }
        return this.stockHistories;
    },
    
    clearCache() {
        this.satuans = null;
        this.suppliers = null;
        this.bahanBakus = null;
        this.stocks = null;
        this.stockHistories = null;
    }
};

// Helper untuk lookup data
async function getSatuanName(satuanId) {
    const satuans = await apiCache.getSatuans();
    const satuan = satuans.find(s => s.id === satuanId);
    return satuan ? satuan.name : 'N/A';
}

async function getBahanBakuName(bahanBakuId) {
    const bahanBakus = await apiCache.getBahanBakus();
    const bahanBaku = bahanBakus.find(b => b.id === bahanBakuId);
    return bahanBaku ? bahanBaku.name : 'N/A';
}

async function getSupplierName(supplierId) {
    const suppliers = await apiCache.getSuppliers();
    const supplier = suppliers.find(s => s.id === supplierId);
    return supplier ? supplier.name : 'N/A';
}
