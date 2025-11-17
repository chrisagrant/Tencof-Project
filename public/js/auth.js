// Authentication and Authorization Management
const AUTH_KEY = 'ten_coffee_auth';
const USERS_KEY = 'ten_coffee_users';

// Initialize default users if not exists
function initializeUsers() {
    const existingUsers = localStorage.getItem(USERS_KEY);
    if (!existingUsers) {
        const defaultUsers = [
            { 
                id: 1, 
                name: 'Owner Ten Coffee', 
                email: 'owner@tencoffee.com', 
                password: 'owner123', 
                role: 'owner' 
            },
            { 
                id: 2, 
                name: 'Admin Ten Coffee', 
                email: 'admin@tencoffee.com', 
                password: 'admin123', 
                role: 'admin' 
            },
            { 
                id: 3, 
                name: 'Kasir Ten Coffee', 
                email: 'kasir@tencoffee.com', 
                password: 'kasir123', 
                role: 'kasir' 
            }
        ];
        localStorage.setItem(USERS_KEY, JSON.stringify(defaultUsers));
    }
}

// Get all users
function getUsers() {
    const users = localStorage.getItem(USERS_KEY);
    return users ? JSON.parse(users) : [];
}

// Save users
function saveUsers(users) {
    localStorage.setItem(USERS_KEY, JSON.stringify(users));
}

// Register new user
function register(name, email, password, role = 'kasir') {
    initializeUsers();
    const users = getUsers();
    
    // Check if email already exists
    if (users.find(u => u.email === email)) {
        return { success: false, message: 'Email sudah terdaftar' };
    }
    
    // Validate role
    if (!['owner', 'admin', 'kasir'].includes(role)) {
        return { success: false, message: 'Role tidak valid' };
    }
    
    const newUser = {
        id: users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1,
        name,
        email,
        password,
        role
    };
    
    users.push(newUser);
    saveUsers(users);
    
    return { success: true, message: 'Registrasi berhasil', user: newUser };
}

// Login user
function login(email, password) {
    initializeUsers();
    const users = getUsers();
    const user = users.find(u => u.email === email && u.password === password);
    
    if (!user) {
        return { success: false, message: 'Email atau password salah' };
    }
    
    // Save session
    const session = {
        id: user.id,
        name: user.name,
        email: user.email,
        role: user.role,
        loginTime: new Date().toISOString()
    };
    
    localStorage.setItem(AUTH_KEY, JSON.stringify(session));
    
    return { success: true, message: 'Login berhasil', user: session };
}

// Logout user
function logout() {
    localStorage.removeItem(AUTH_KEY);
    window.location.href = '/login';
}

// Get current user session
function getCurrentUser() {
    const session = localStorage.getItem(AUTH_KEY);
    return session ? JSON.parse(session) : null;
}

// Check if user is authenticated
function isAuthenticated() {
    return getCurrentUser() !== null;
}

// Check user role
function hasRole(role) {
    const user = getCurrentUser();
    if (!user) return false;
    
    if (Array.isArray(role)) {
        return role.includes(user.role);
    }
    return user.role === role;
}

// Role permissions
const PERMISSIONS = {
    owner: {
        canViewDashboard: true,
        canManageBahanBaku: true,
        canManageSatuan: true,
        canManageSupplier: true,
        canManageStock: true,
        canViewStockHistory: true,
        canManageUsers: true
    },
    admin: {
        canViewDashboard: true,
        canManageBahanBaku: true,
        canManageSatuan: true,
        canManageSupplier: true,
        canManageStock: true,
        canViewStockHistory: true,
        canManageUsers: false
    },
    kasir: {
        canViewDashboard: true,
        canManageBahanBaku: false,
        canManageSatuan: false,
        canManageSupplier: false,
        canManageStock: false,
        canViewStockHistory: true,
        canManageUsers: false
    }
};

// Check permission
function hasPermission(permission) {
    const user = getCurrentUser();
    if (!user) return false;
    
    const rolePermissions = PERMISSIONS[user.role];
    return rolePermissions ? rolePermissions[permission] : false;
}

// Require authentication (call on protected pages)
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/login';
        return false;
    }
    return true;
}

// Export functions for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeUsers,
        register,
        login,
        logout,
        getCurrentUser,
        isAuthenticated,
        hasRole,
        hasPermission,
        requireAuth
    };
}