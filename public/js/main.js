// Real application data - will be loaded from API
let appData = {
    users: [],
    satuans: [],
    suppliers: [],
    bahanBakus: [],
    stocks: [],
    stockHistory: []
};

let currentPage = 'dashboard';
let currentPageNum = 1;
const itemsPerPage = 5;
let filteredData = [];

// Load data from API on page load
async function loadAppData() {
    try {
        // Load all data from API in parallel
        const [satuansRes, suppliersRes, bahanBakusRes, stocksRes, historiesRes] = await Promise.all([
            SatuanAPI.getAll(),
            SupplierAPI.getAll(),
            BahanBakuAPI.getAll(),
            StockAPI.getAll(),
            StockHistoryAPI.getAll()
        ]);

        appData.satuans = satuansRes.data || [];
        appData.suppliers = suppliersRes.data || [];
        appData.bahanBakus = bahanBakusRes.data || [];
        appData.stocks = stocksRes.data || [];
        appData.stockHistory = historiesRes.data || [];

        console.log('✓ Data loaded from API', appData);
    } catch (error) {
        console.error('Error loading app data:', error);
        showToast('Error loading data from server', 'error');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    await loadAppData();
    renderPage();
});

// Mock data fallback (for reference only)
const mockData = {
    users: [],
    satuans: appData.satuans,
    suppliers: appData.suppliers,
    bahanBakus: appData.bahanBakus,
    stocks: appData.stocks,
    stockHistory: appData.stockHistory
};

function getNextId(dataArray) {
    return dataArray.length > 0 ? Math.max(...dataArray.map(item => item.id)) + 1 : 1;
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };
    
    toast.innerHTML = `
        <span class="toast-icon">${icons[type]}</span>
        <span class="toast-message">${message}</span>
    `;
    
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function openModal(title, content) {
    const modal = document.getElementById('modal');
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-body').innerHTML = content;
    modal.classList.add('active');
}

function closeModal() {
    document.getElementById('modal').classList.remove('active');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function getSatuanName(satuanId) {
    const satuan = appData.satuans.find(s => s.id === satuanId);
    return satuan ? satuan.name : 'N/A';
}

function getBahanBakuName(bahanBakuId) {
    const bahanBaku = appData.bahanBakus.find(b => b.id === bahanBakuId);
    return bahanBaku ? bahanBaku.name : 'N/A';
}

function getSupplierName(supplierId) {
    const supplier = appData.suppliers.find(s => s.id === supplierId);
    return supplier ? supplier.name : 'N/A';
}

function getUserName(userId) {
    const user = appData.users.find(u => u.id === userId);
    return user ? user.name : 'N/A';
}

function canManage() {
    const user = getCurrentUser();
    return user && (user.role === 'owner' || user.role === 'admin');
}

function renderPagination(totalItems, currentPage, onPageChange) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    let html = '<div class="pagination">';
    
    if (currentPage > 1) {
        html += `<button onclick="handlePageChange(${currentPage - 1})">← Previous</button>`;
    }
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            html += `<button class="active" disabled>${i}</button>`;
        } else {
            html += `<button onclick="handlePageChange(${i})">${i}</button>`;
        }
    }
    
    if (currentPage < totalPages) {
        html += `<button onclick="handlePageChange(${currentPage + 1})">Next →</button>`;
    }
    
    html += '</div>';
    return html;
}

function handlePageChange(page) {
    currentPageNum = page;
    renderPage();
}

function getPaginatedData(data) {
    const start = (currentPageNum - 1) * itemsPerPage;
    return data.slice(start, start + itemsPerPage);
}

function renderDashboard() {
    const totalBahanBaku = appData.bahanBakus.length;
    const totalStock = appData.stocks.reduce((sum, s) => sum + s.quantity, 0);
    const totalSupplier = appData.suppliers.length;
    
    const recentHistory = appData.stockHistory.slice(-5).reverse();
    
    let historyHtml = '';
    if (recentHistory.length === 0) {
        historyHtml = '<tr><td colspan="5" style="text-align: center; padding: 40px;">Tidak ada aktivitas</td></tr>';
    } else {
        historyHtml = recentHistory.map(h => `
            <tr>
                <td>${getBahanBakuName(h.bahan_baku_id)}</td>
                <td><span style="background-color: ${h.type === 'IN' ? '#d4edda' : '#f8d7da'}; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">${h.type}</span></td>
                <td>${h.quantity}</td>
                <td>${getUserName(h.created_by)}</td>
                <td>${formatDate(h.created_at)}</td>
            </tr>
        `).join('');
    }
    
    const content = `
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Total Bahan Baku</h3>
                <div class="stat-value">${totalBahanBaku}</div>
            </div>
            <div class="stat-card">
                <h3>Total Stok</h3>
                <div class="stat-value">${totalStock}</div>
            </div>
            <div class="stat-card">
                <h3>Total Supplier</h3>
                <div class="stat-value">${totalSupplier}</div>
            </div>
        </div>
        
        <h3 class="section-title">Aktivitas Stok Terakhir</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    ${historyHtml}
                </tbody>
            </table>
        </div>
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderBahanBaku() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    filteredData = appData.bahanBakus.filter(b => 
        b.name.toLowerCase().includes(searchTerm)
    );
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="4" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(b => `
            <tr>
                <td>${b.name}</td>
                <td>${getSatuanName(b.satuan_id)}</td>
                <td>${getUserName(b.created_by)}</td>
                <td>
                    <div class="action-buttons">
                        ${canManage() ? `
                            <button class="btn btn-small" onclick="editBahanBaku(${b.id})">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteBahanBaku(${b.id})">Hapus</button>
                        ` : '<span style="color: #999;">Read-only</span>'}
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    const content = `
        ${canManage() ? '<button class="btn btn-primary btn-add" onclick="openBahanBakuForm()">+ Tambah Bahan Baku</button>' : ''}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Satuan</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderSatuan() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    filteredData = appData.satuans.filter(s => 
        s.name.toLowerCase().includes(searchTerm)
    );
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="2" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(s => `
            <tr>
                <td>${s.name}</td>
                <td>
                    <div class="action-buttons">
                        ${canManage() ? `
                            <button class="btn btn-small" onclick="editSatuan(${s.id})">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteSatuan(${s.id})">Hapus</button>
                        ` : '<span style="color: #999;">Read-only</span>'}
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    const content = `
        ${canManage() ? '<button class="btn btn-primary btn-add" onclick="openSatuanForm()">+ Tambah Satuan</button>' : ''}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderSupplier() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    filteredData = appData.suppliers.filter(s => 
        s.name.toLowerCase().includes(searchTerm) ||
        s.phone.toLowerCase().includes(searchTerm) ||
        s.address.toLowerCase().includes(searchTerm)
    );
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="4" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(s => `
            <tr>
                <td>${s.name}</td>
                <td>${s.phone}</td>
                <td>${s.address}</td>
                <td>
                    <div class="action-buttons">
                        ${canManage() ? `
                            <button class="btn btn-small" onclick="editSupplier(${s.id})">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteSupplier(${s.id})">Hapus</button>
                        ` : '<span style="color: #999;">Read-only</span>'}
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    const content = `
        ${canManage() ? '<button class="btn btn-primary btn-add" onclick="openSupplierForm()">+ Tambah Supplier</button>' : ''}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderStock() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    filteredData = appData.stocks.filter(s => {
        const bahanBaku = appData.bahanBakus.find(b => b.id === s.bahan_baku_id);
        return bahanBaku && bahanBaku.name.toLowerCase().includes(searchTerm);
    });
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="6" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(s => `
            <tr>
                <td>${getBahanBakuName(s.bahan_baku_id)}</td>
                <td>${s.quantity}</td>
                <td>Rp ${s.unit_price.toLocaleString('id-ID')}</td>
                <td>${getSupplierName(s.supplier_id)}</td>
                <td>${getUserName(s.created_by)}</td>
                <td>
                    <div class="action-buttons">
                        ${canManage() ? `
                            <button class="btn btn-small" onclick="editStock(${s.id})">Edit</button>
                            <button class="btn btn-small btn-danger" onclick="deleteStock(${s.id})">Hapus</button>
                        ` : '<span style="color: #999;">Read-only</span>'}
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    const content = `
        ${canManage() ? '<button class="btn btn-primary btn-add" onclick="openStockForm()">+ Tambah Stock</button>' : ''}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Jumlah</th>
                        <th>Harga Unit</th>
                        <th>Supplier</th>
                        <th>Dibuat Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderStockHistory() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const filterType = document.getElementById('filter-type')?.value || '';
    const filterBahanBaku = document.getElementById('filter-bahan-baku')?.value || '';
    
    filteredData = appData.stockHistory.filter(h => {
        const bahanBaku = appData.bahanBakus.find(b => b.id === h.bahan_baku_id);
        const matchesSearch = bahanBaku && bahanBaku.name.toLowerCase().includes(searchTerm);
        const matchesType = !filterType || h.type === filterType;
        const matchesBahanBaku = !filterBahanBaku || h.bahan_baku_id === parseInt(filterBahanBaku);
        return matchesSearch && matchesType && matchesBahanBaku;
    });
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="5" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(h => `
            <tr>
                <td>${getBahanBakuName(h.bahan_baku_id)}</td>
                <td><span style="background-color: ${h.type === 'IN' ? '#d4edda' : '#f8d7da'}; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">${h.type}</span></td>
                <td>${h.quantity}</td>
                <td>${getUserName(h.created_by)}</td>
                <td>${formatDate(h.created_at)}</td>
            </tr>
        `).join('');
    }
    
    const satuanOptions = appData.bahanBakus.map(b => 
        `<option value="${b.id}">${b.name}</option>`
    ).join('');
    
    const content = `
        <div class="filter-group">
            <select id="filter-bahan-baku" onchange="renderStockHistory()">
                <option value="">Semua Bahan Baku</option>
                ${satuanOptions}
            </select>
            <select id="filter-type" onchange="renderStockHistory()">
                <option value="">Semua Tipe</option>
                <option value="IN">IN</option>
                <option value="OUT">OUT</option>
            </select>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Bahan Baku</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function renderUsers() {
    const user = getCurrentUser();
    const allUsers = JSON.parse(localStorage.getItem('ten_coffee_users')) || appData.users;
    
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    filteredData = allUsers.filter(u => 
        u.name.toLowerCase().includes(searchTerm) ||
        u.email.toLowerCase().includes(searchTerm)
    );
    
    const paginatedData = getPaginatedData(filteredData);
    
    let tableHtml = '';
    if (paginatedData.length === 0) {
        tableHtml = '<tr><td colspan="4" style="text-align: center; padding: 40px;">Tidak ada data</td></tr>';
    } else {
        tableHtml = paginatedData.map(u => `
            <tr>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td><span style="background-color: #e0e0e0; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">${u.role}</span></td>
                <td>${u.id === user.id ? '<span style="color: #2ecc71; font-weight: 600;">You</span>' : 'Active'}</td>
            </tr>
        `).join('');
    }
    
    const content = `
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableHtml}
                </tbody>
            </table>
        </div>
        ${renderPagination(filteredData.length, currentPageNum)}
    `;
    
    document.getElementById('content').innerHTML = content;
}

function openBahanBakuForm(id = null) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const bahanBaku = id ? appData.bahanBakus.find(b => b.id === id) : null;
    const title = bahanBaku ? 'Edit Bahan Baku' : 'Tambah Bahan Baku';
    
    const satuanOptions = appData.satuans.map(s => 
        `<option value="${s.id}" ${bahanBaku && bahanBaku.satuan_id === s.id ? 'selected' : ''}>${s.name}</option>`
    ).join('');
    
    const formHtml = `
        <form onsubmit="saveBahanBaku(event, ${id || 'null'})">
            <div class="form-group">
                <label>Nama Bahan Baku</label>
                <input type="text" id="bahan-baku-name" value="${bahanBaku ? bahanBaku.name : ''}" required>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <select id="bahan-baku-satuan" required>
                    ${satuanOptions}
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    `;
    
    openModal(title, formHtml);
}

function saveBahanBaku(event, id) {
    event.preventDefault();
    
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const name = document.getElementById('bahan-baku-name').value;
    const satuanId = parseInt(document.getElementById('bahan-baku-satuan').value);
    const user = getCurrentUser();
    
    if (id) {
        const bahanBaku = appData.bahanBakus.find(b => b.id === id);
        bahanBaku.name = name;
        bahanBaku.satuan_id = satuanId;
        showToast('Bahan Baku berhasil diperbarui', 'success');
    } else {
        appData.bahanBakus.push({
            id: getNextId(appData.bahanBakus),
            name,
            satuan_id: satuanId,
            created_by: user.id
        });
        showToast('Bahan Baku berhasil ditambahkan', 'success');
    }
    
    closeModal();
    currentPageNum = 1;
    renderPage();
}

function editBahanBaku(id) {
    openBahanBakuForm(id);
}

function deleteBahanBaku(id) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menghapus bahan baku ini?')) {
        appData.bahanBakus = appData.bahanBakus.filter(b => b.id !== id);
        appData.stocks = appData.stocks.filter(s => s.bahan_baku_id !== id);
        appData.stockHistory = appData.stockHistory.filter(h => h.bahan_baku_id !== id);
        showToast('Bahan Baku berhasil dihapus', 'success');
        currentPageNum = 1;
        renderPage();
    }
}

function openSatuanForm(id = null) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const satuan = id ? appData.satuans.find(s => s.id === id) : null;
    const title = satuan ? 'Edit Satuan' : 'Tambah Satuan';
    
    const formHtml = `
        <form onsubmit="saveSatuan(event, ${id || 'null'})">
            <div class="form-group">
                <label>Nama Satuan</label>
                <input type="text" id="satuan-name" value="${satuan ? satuan.name : ''}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    `;
    
    openModal(title, formHtml);
}

function saveSatuan(event, id) {
    event.preventDefault();
    
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const name = document.getElementById('satuan-name').value;
    
    if (id) {
        const satuan = appData.satuans.find(s => s.id === id);
        satuan.name = name;
        showToast('Satuan berhasil diperbarui', 'success');
    } else {
        appData.satuans.push({
            id: getNextId(appData.satuans),
            name
        });
        showToast('Satuan berhasil ditambahkan', 'success');
    }
    
    closeModal();
    currentPageNum = 1;
    renderPage();
}

function editSatuan(id) {
    openSatuanForm(id);
}

function deleteSatuan(id) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menghapus satuan ini?')) {
        appData.satuans = appData.satuans.filter(s => s.id !== id);
        showToast('Satuan berhasil dihapus', 'success');
        currentPageNum = 1;
        renderPage();
    }
}

function openSupplierForm(id = null) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const supplier = id ? appData.suppliers.find(s => s.id === id) : null;
    const title = supplier ? 'Edit Supplier' : 'Tambah Supplier';
    
    const formHtml = `
        <form onsubmit="saveSupplier(event, ${id || 'null'})">
            <div class="form-group">
                <label>Nama Supplier</label>
                <input type="text" id="supplier-name" value="${supplier ? supplier.name : ''}" required>
            </div>
            <div class="form-group">
                <label>Telepon</label>
                <input type="tel" id="supplier-phone" value="${supplier ? supplier.phone : ''}" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <textarea id="supplier-address" required>${supplier ? supplier.address : ''}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    `;
    
    openModal(title, formHtml);
}

function saveSupplier(event, id) {
    event.preventDefault();
    
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const name = document.getElementById('supplier-name').value;
    const phone = document.getElementById('supplier-phone').value;
    const address = document.getElementById('supplier-address').value;
    
    if (id) {
        const supplier = appData.suppliers.find(s => s.id === id);
        supplier.name = name;
        supplier.phone = phone;
        supplier.address = address;
        showToast('Supplier berhasil diperbarui', 'success');
    } else {
        appData.suppliers.push({
            id: getNextId(appData.suppliers),
            name,
            phone,
            address
        });
        showToast('Supplier berhasil ditambahkan', 'success');
    }
    
    closeModal();
    currentPageNum = 1;
    renderPage();
}

function editSupplier(id) {
    openSupplierForm(id);
}

function deleteSupplier(id) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menghapus supplier ini?')) {
        appData.suppliers = appData.suppliers.filter(s => s.id !== id);
        showToast('Supplier berhasil dihapus', 'success');
        currentPageNum = 1;
        renderPage();
    }
}

function openStockForm(id = null) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const stock = id ? appData.stocks.find(s => s.id === id) : null;
    const title = stock ? 'Edit Stock' : 'Tambah Stock';
    
    const bahanBakuOptions = appData.bahanBakus.map(b => 
        `<option value="${b.id}" ${stock && stock.bahan_baku_id === b.id ? 'selected' : ''}>${b.name}</option>`
    ).join('');
    
    const supplierOptions = appData.suppliers.map(s => 
        `<option value="${s.id}" ${stock && stock.supplier_id === s.id ? 'selected' : ''}>${s.name}</option>`
    ).join('');
    
    const formHtml = `
        <form onsubmit="saveStock(event, ${id || 'null'})">
            <div class="form-group">
                <label>Bahan Baku</label>
                <select id="stock-bahan-baku" required>
                    <option value="">Pilih Bahan Baku</option>
                    ${bahanBakuOptions}
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" id="stock-quantity" value="${stock ? stock.quantity : ''}" required min="1">
                </div>
                <div class="form-group">
                    <label>Harga Unit (Rp)</label>
                    <input type="number" id="stock-unit-price" value="${stock ? stock.unit_price : ''}" required min="0">
                </div>
            </div>
            <div class="form-group">
                <label>Supplier</label>
                <select id="stock-supplier" required>
                    <option value="">Pilih Supplier</option>
                    ${supplierOptions}
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    `;
    
    openModal(title, formHtml);
}

function saveStock(event, id) {
    event.preventDefault();
    
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    const bahanBakuId = parseInt(document.getElementById('stock-bahan-baku').value);
    const quantity = parseInt(document.getElementById('stock-quantity').value);
    const unitPrice = parseInt(document.getElementById('stock-unit-price').value);
    const supplierId = parseInt(document.getElementById('stock-supplier').value);
    const user = getCurrentUser();
    
    if (id) {
        const stock = appData.stocks.find(s => s.id === id);
        const oldQuantity = stock.quantity;
        stock.quantity = quantity;
        stock.unit_price = unitPrice;
        stock.supplier_id = supplierId;
        
        if (oldQuantity !== quantity) {
            const diff = quantity - oldQuantity;
            appData.stockHistory.push({
                id: getNextId(appData.stockHistory),
                bahan_baku_id: bahanBakuId,
                type: diff > 0 ? 'IN' : 'OUT',
                quantity: Math.abs(diff),
                created_by: user.id,
                created_at: new Date().toISOString()
            });
        }
        
        showToast('Stock berhasil diperbarui', 'success');
    } else {
        appData.stocks.push({
            id: getNextId(appData.stocks),
            bahan_baku_id: bahanBakuId,
            quantity,
            unit_price: unitPrice,
            supplier_id: supplierId,
            created_by: user.id
        });
        
        appData.stockHistory.push({
            id: getNextId(appData.stockHistory),
            bahan_baku_id: bahanBakuId,
            type: 'IN',
            quantity,
            created_by: user.id,
            created_at: new Date().toISOString()
        });
        
        showToast('Stock berhasil ditambahkan', 'success');
    }
    
    closeModal();
    currentPageNum = 1;
    renderPage();
}

function editStock(id) {
    openStockForm(id);
}

function deleteStock(id) {
    if (!canManage()) {
        showToast('Anda tidak memiliki akses untuk mengelola data ini', 'error');
        return;
    }
    
    if (confirm('Apakah Anda yakin ingin menghapus stock ini?')) {
        appData.stocks = appData.stocks.filter(s => s.id !== id);
        showToast('Stock berhasil dihapus', 'success');
        currentPageNum = 1;
        renderPage();
    }
}

function renderPage() {
    document.getElementById('page-title').textContent = 
        currentPage === 'dashboard' ? 'Dashboard' :
        currentPage === 'bahan-baku' ? 'Bahan Baku' :
        currentPage === 'satuan' ? 'Satuan' :
        currentPage === 'supplier' ? 'Supplier' :
        currentPage === 'stock' ? 'Stock' :
        currentPage === 'stock-history' ? 'Stock History' :
        currentPage === 'users' ? 'Users' : 'Dashboard';
    
    switch(currentPage) {
        case 'dashboard':
            renderDashboard();
            break;
        case 'bahan-baku':
            renderBahanBaku();
            break;
        case 'satuan':
            renderSatuan();
            break;
        case 'supplier':
            renderSupplier();
            break;
        case 'stock':
            renderStock();
            break;
        case 'stock-history':
            renderStockHistory();
            break;
        case 'users':
            renderUsers();
            break;
        default:
            renderDashboard();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            currentPage = this.dataset.page;
            currentPageNum = 1;
            document.getElementById('search-input').value = '';
            renderPage();
        });
    });
    
    document.getElementById('modal-close').addEventListener('click', closeModal);
    
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.getElementById('search-input').addEventListener('input', function() {
        currentPageNum = 1;
        renderPage();
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    
    renderPage();
});
