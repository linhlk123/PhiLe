// staff.js - simple in-memory staff dashboard interactions
const sampleReservations = [
  {id: 'R001', guest: 'Nguyễn Văn A', room: 'Deluxe Suite', dates: '2025-11-01 → 2025-11-03', status: 'pending', email: 'guest1@example.com', phone: '0901234567', total: '2,400,000 VND'},
  {id: 'R002', guest: 'Trần Thị B', room: 'Sea View', dates: '2025-11-05 → 2025-11-07', status: 'confirmed', email: 'guest2@example.com', phone: '0907654321', total: '5,800,000 VND'},
];

const rooms = [
  {code:'101', type:'Deluxe Suite', status:'available'},
  {code:'102', type:'Sea View', status:'occupied'},
  {code:'103', type:'Garden Villa', status:'cleaning'},
  {code:'104', type:'Sea View', status:'maintenance'},
];

function renderReservations(){
  const el = document.getElementById('reservationsList');
  el.innerHTML = '';
  sampleReservations.forEach(r=>{
    const div = document.createElement('div');
    div.className = 'reservation';
    div.innerHTML = `\
      <div class="meta">\
        <strong>${r.id} — ${r.guest}</strong>\
        <p>${r.room} · ${r.dates}</p>\
        <p>${r.email} · ${r.phone}</p>\
      </div>\
      <div>\
        <div class="actions">\
          <span class="badge ${r.status==='pending'?'pending':r.status==='confirmed'?'confirmed':'checkedin'}">${r.status}</span>\
        </div>\
        <div style="margin-top:8px;display:flex;flex-direction:column;gap:6px">\
          <button class="btn-primary" data-action="confirm" data-id="${r.id}">Xác nhận</button>\
          <button class="btn-ghost" data-action="checkin" data-id="${r.id}">Check-in</button>\
          <button class="btn-ghost" data-action="checkout" data-id="${r.id}">Check-out</button>\
          <button class="btn-ghost" data-action="email" data-id="${r.id}">Gửi email</button>\
          <button class="btn-ghost" data-action="print" data-id="${r.id}">In hóa đơn</button>\
        </div>\
      </div>`;
    el.appendChild(div);
  });
}

function renderRooms(){
  const el = document.getElementById('roomsStatus');
  el.innerHTML = '';
  rooms.forEach(r=>{
    const row = document.createElement('div');
    row.className = 'room-row';
    row.innerHTML = `\
      <div>${r.code} · ${r.type}</div>\
      <div>\
        <select data-room="${r.code}">\
          <option value="available" ${r.status==='available'?'selected':''}>Đang trống</option>\
          <option value="booked" ${r.status==='booked'?'selected':''}>Đã đặt</option>\
          <option value="occupied" ${r.status==='occupied'?'selected':''}>Đang ở</option>\
          <option value="cleaning" ${r.status==='cleaning'?'selected':''}>Đang dọn</option>\
          <option value="maintenance" ${r.status==='maintenance'?'selected':''}>Đang sửa chữa</option>\
        </select>\
      </div>`;
    el.appendChild(row);
  });
}

document.addEventListener('DOMContentLoaded', ()=>{
  renderReservations();
  renderRooms();

  // Reservation actions
  document.getElementById('reservationsList').addEventListener('click', (e)=>{
    const btn = e.target.closest('button');
    if(!btn) return;
    const action = btn.dataset.action;
    const id = btn.dataset.id;
    const idx = sampleReservations.findIndex(r=>r.id===id);
    if(idx===-1) return;
    const res = sampleReservations[idx];
    if(action==='confirm'){
      res.status='confirmed';
      alert(`Đơn ${id} đã được xác nhận`);
    } else if(action==='checkin'){
      res.status='checkedin';
      alert(`Khách ${res.guest} đã check-in`);
    } else if(action==='checkout'){
      res.status='completed';
      alert(`Khách ${res.guest} đã check-out`);
    } else if(action==='email'){
      // simulate email
      alert(`Gửi email xác nhận đến ${res.email}`);
    } else if(action==='print'){
      // open a printable invoice window
      const invoice = window.open('', '_blank');
      invoice.document.write(`<h2>Hóa đơn - ${res.id}</h2><p>Khách: ${res.guest}</p><p>Phòng: ${res.room}</p><p>Thời gian: ${res.dates}</p><p>Tổng: ${res.total}</p>`);
      invoice.print();
    }
    renderReservations();
  });

  // Room status change
  document.getElementById('roomsStatus').addEventListener('change', (e)=>{
    const sel = e.target.closest('select');
    if(!sel) return;
    const code = sel.dataset.room;
    const room = rooms.find(r=>r.code===code);
    room.status = sel.value;
    alert(`Cập nhật trạng thái phòng ${code} → ${sel.value}`);
    renderRooms();
  });

  document.getElementById('sendTestEmail').addEventListener('click', ()=>{
    alert('Gửi email mẫu đến guest@example.com (mô phỏng)');
  });

  document.getElementById('printReport').addEventListener('click', ()=>{
    window.print();
  });
});
// staff.js - simple UI logic for staff dashboard
(function(){
  // Sample data - in a real app this would be fetched from an API
  const rooms = [
    {id:101,type:'Deluxe Garden view',status:'available'},
    {id:102,type:'Superior Garden view',status:'booked'},
    {id:103,type:'Deluxe Rice Field View',status:'cleaning'},
    {id:104,type:'Palm View Villa Room',status:'maintenance'},
    {id:105,type:'Junior Suite',status:'available'},
    {id:201,type:'2-Bedroom Pool Villa',status:'available'},
    {id:202,type:'4-Bedroom Pool Villa',status:'booked'}
  ];

  const bookings = [
    {id:'B001', guest:'Nguyễn Văn A', room:102, date:'2025-11-02', status:'pending'},
    {id:'B002', guest:'Trần Thị B', room:103, date:'2025-11-05', status:'pending'},
    {id:'B003', guest:'Lê C', room:105, date:'2025-11-10', status:'confirmed'},
    {id:'B004', guest:'Phạm D', room:201, date:'2025-11-12', status:'confirmed'}
  ];

  const customers = [
    {id:'C001', name:'Nguyễn Văn A', email:'a@example.com', phone:'0900000001'},
    {id:'C002', name:'Trần Thị B', email:'b@example.com', phone:'0900000002'},
    {id:'C003', name:'Lê C', email:'c@example.com', phone:'0900000003'},
    {id:'C004', name:'Phạm D', email:'d@example.com', phone:'0900000004'}
  ];

  const staff = [
    {id:'S001', name:'Nhân viên A', role:'Lễ tân', email:'staffA@example.com', phone:'0911111111'},
    {id:'S002', name:'Nhân viên B', role:'Kế toán', email:'staffB@example.com', phone:'0912222222'}
  ];

  // DOM refs
  const bookingsBody = document.getElementById('bookingsBody');
  const roomsGrid = document.getElementById('roomsGrid');
  const detailsContent = document.getElementById('detailsContent');

  function statusClassForRoom(r){
    switch(r){
      case 'available': return 'status-available';
      case 'booked': return 'status-booked';
      case 'cleaning': return 'status-cleaning';
      case 'maintenance': return 'status-maintain';
      default: return '';
    }
  }

  function renderBookings(){
    bookingsBody.innerHTML = '';
    bookings.forEach(b=>{
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${b.id}</td>
        <td>${b.guest}</td>
        <td>${b.room}</td>
        <td>${b.date}</td>
        <td>${b.status}</td>
        <td>
          ${b.status==='pending'?`<button class="btn btn-confirm" data-id="${b.id}">Xác nhận</button>`:''}
          <button class="btn btn-checkin" data-id="${b.id}">Check-in</button>
          <button class="btn btn-checkout" data-id="${b.id}">Check-out</button>
          <button class="btn btn-invoice" data-id="${b.id}">Hóa đơn</button>
        </td>
      `;
      bookingsBody.appendChild(tr);
    });
  }

  function renderRooms(){
    roomsGrid.innerHTML = '';
    const filtered = window._currentRoomTypeFilter ? rooms.filter(x=>x.type===window._currentRoomTypeFilter) : rooms;
    filtered.forEach(r=>{
      const div = document.createElement('div');
      div.className = 'room-card';
      div.innerHTML = `
        <div class="room-title">Phòng ${r.id} — ${r.type}</div>
        <div class="room-status ${statusClassForRoom(r.status)}">${r.status}</div>
        <div class="room-actions">
          <button data-room="${r.id}" class="btn btn-change">Thay đổi</button>
        </div>
      `;
      div.querySelector('.btn-change').addEventListener('click', ()=>{
        showRoomActions(r);
      });
      roomsGrid.appendChild(div);
    });
  }

  function uniqueRoomTypes(){
    const map = {};
    rooms.forEach(r=>{ map[r.type] = (map[r.type]||0)+1 });
    return Object.keys(map).map(t=>({type:t,count:map[t]}));
  }

  function renderRoomTypesSidebar(){
    const list = document.getElementById('roomTypesList');
    list.innerHTML = '';
    const types = uniqueRoomTypes();
    const allLi = document.createElement('li');
    allLi.textContent = 'Tất cả phòng';
    allLi.className = 'roomtype-item';
    allLi.addEventListener('click', ()=>{
      window._currentRoomTypeFilter = null; renderRooms();
    });
    list.appendChild(allLi);
    types.forEach(t=>{
      const li = document.createElement('li');
      li.className = 'roomtype-item';
      li.innerHTML = `${t.type} <span class="count">${t.count}</span>`;
      li.addEventListener('click', ()=>{
        window._currentRoomTypeFilter = t.type; renderRooms();
      });
      list.appendChild(li);
    });
  }

  function renderKPIs(){
    // simple simulated KPIs
    const revenue = bookings.reduce((s,b)=> s + (b.status==='confirmed' || b.status==='checked-in' ? 150 : 0), 0);
    const totalRooms = rooms.length;
    const occupied = rooms.filter(r=>r.status!=='available').length;
    const occupancyPercent = Math.round((occupied/totalRooms)*100);
    const kpiEl = document.getElementById('kpiRevenue');
    if(kpiEl) kpiEl.textContent = revenue.toLocaleString('vi-VN') + ' VND';
    const kpiOcc = document.getElementById('kpiOccupancy');
    if(kpiOcc) kpiOcc.textContent = occupancyPercent + '%';
    const kpiBook = document.getElementById('kpiBookings');
    if(kpiBook) kpiBook.textContent = bookings.length;
    // also update main-panel KPIs if present
    const mk = document.getElementById('mainKpiRevenue'); if(mk) mk.textContent = revenue.toLocaleString('vi-VN') + ' VND';
    const mo = document.getElementById('mainKpiOccupancy'); if(mo) mo.textContent = occupancyPercent + '%';
    const mb = document.getElementById('mainKpiBookings'); if(mb) mb.textContent = bookings.length;
  }

  function renderCustomers(list){
    const el = document.getElementById('customerResults');
    el.innerHTML = '';
    (list||customers).forEach(c=>{
      const li = document.createElement('li');
      li.innerHTML = `<strong>${c.name}</strong> <div class="small">${c.email} • ${c.phone}</div>`;
      el.appendChild(li);
    });
    // mirror to main panel results if present
    const mainEl = document.getElementById('mainCustomerResults');
    if(mainEl){
      mainEl.innerHTML = '';
      (list||customers).forEach(c=>{
        const li = document.createElement('li');
        li.innerHTML = `<strong>${c.name}</strong> <div class="small">${c.email} • ${c.phone}</div>`;
        mainEl.appendChild(li);
      });
    }
  }

  // Staff rendering and edit functions
  function renderStaffList(){
    const el = document.getElementById('staffList');
    if(!el) return;
    el.innerHTML = '';
    staff.forEach(s=>{
      const li = document.createElement('li');
      li.innerHTML = `<div><strong>${s.name}</strong><div class="small">${s.role} • ${s.email}</div></div><div><button class="edit-staff" data-id="${s.id}">Sửa</button></div>`;
      li.querySelector('.edit-staff').addEventListener('click', ()=>openStaffForm(s.id));
      el.appendChild(li);
    });
  }

  function openStaffForm(id){
    const s = staff.find(x=>x.id===id); if(!s) return;
    document.getElementById('staffId').value = s.id;
    document.getElementById('staffName').value = s.name;
    document.getElementById('staffRole').value = s.role;
    document.getElementById('staffEmail').value = s.email;
    document.getElementById('staffPhone').value = s.phone;
    document.getElementById('staffForm').scrollIntoView({behavior:'smooth'});
  }

  function saveStaffFromForm(){
    const id = document.getElementById('staffId').value;
    const s = staff.find(x=>x.id===id);
    if(!s) return alert('Không tìm thấy nhân viên');
    s.name = document.getElementById('staffName').value;
    s.role = document.getElementById('staffRole').value;
    s.email = document.getElementById('staffEmail').value;
    s.phone = document.getElementById('staffPhone').value;
    renderStaffList();
    renderCustomers();
    alert('Đã lưu thông tin nhân viên (mô phỏng)');
  }

  function cancelStaffEdit(){
    document.getElementById('staffForm').reset();
  }

  function switchMainPanel(panel){
    document.getElementById('panelRooms').style.display = panel==='rooms' ? 'block' : 'none';
    document.getElementById('panelStaff').style.display = panel==='staff' ? 'block' : 'none';
    document.getElementById('panelCustomers').style.display = panel==='customers' ? 'block' : 'none';
    if(panel==='staff' || panel==='rooms') renderKPIs();
  }

  function showRoomActions(room){
    detailsContent.innerHTML = `
      <h3>Phòng ${room.id} — ${room.type}</h3>
      <p>Trạng thái hiện tại: <strong>${room.status}</strong></p>
      <div class="detail-actions">
        <button id="setAvailable">Đặt trống</button>
        <button id="setBooked">Đặt trước</button>
        <button id="setCleaning">Đang dọn</button>
        <button id="setMaintain">Sửa chữa</button>
      </div>
    `;
    document.getElementById('setAvailable').addEventListener('click', ()=>changeRoomStatus(room.id,'available'));
    document.getElementById('setBooked').addEventListener('click', ()=>changeRoomStatus(room.id,'booked'));
    document.getElementById('setCleaning').addEventListener('click', ()=>changeRoomStatus(room.id,'cleaning'));
    document.getElementById('setMaintain').addEventListener('click', ()=>changeRoomStatus(room.id,'maintenance'));
  }

  function changeRoomStatus(roomId, status){
    const r = rooms.find(x=>x.id===roomId);
    if(!r) return;
    r.status = status;
    renderRooms();
    detailsContent.innerHTML = `<p>Đã cập nhật trạng thái phòng ${roomId} => ${status}</p>`;
  }

  function findBooking(id){
    return bookings.find(b=>b.id===id);
  }

  function confirmBooking(id){
    const b = findBooking(id);
    if(!b) return alert('Không tìm thấy đặt phòng');
    b.status = 'confirmed';
    // mark room booked
    const r = rooms.find(x=>x.id===b.room);
    if(r) r.status = 'booked';
    renderBookings(); renderRooms();
    detailsContent.innerHTML = `<p>Đã xác nhận đặt phòng ${id} cho ${b.guest} — Phòng ${b.room}</p>`;
  }

  function checkIn(id){
    const b = findBooking(id);
    if(!b) return alert('Không tìm thấy đặt phòng');
    b.status = 'checked-in';
    const r = rooms.find(x=>x.id===b.room);
    if(r) r.status = 'booked';
    renderBookings(); renderRooms();
    detailsContent.innerHTML = `<p>Khách ${b.guest} đã check-in vào phòng ${b.room}.</p>`;
  }

  function checkOut(id){
    const b = findBooking(id);
    if(!b) return alert('Không tìm thấy đặt phòng');
    b.status = 'checked-out';
    const r = rooms.find(x=>x.id===b.room);
    if(r) r.status = 'available';
    renderBookings(); renderRooms();
    detailsContent.innerHTML = `<p>Khách ${b.guest} đã check-out khỏi phòng ${b.room}.</p>`;
  }

  function sendInvoice(id){
    const b = findBooking(id);
    if(!b) return alert('Không tìm thấy đặt phòng');
    // Simulate sending email / printing
    detailsContent.innerHTML = `<p>Hóa đơn cho ${b.guest} (Đặt: ${b.id}) đã được gửi/In. (Mô phỏng)</p>`;
    console.log('Invoice simulated for', id);
  }

  // global action handlers
  function attachActionHandlers(){
    document.addEventListener('click', function(e){
      const el = e.target;
      if(el.matches('.btn-confirm')) confirmBooking(el.dataset.id);
      if(el.matches('.btn-checkin')) checkIn(el.dataset.id);
      if(el.matches('.btn-checkout')) checkOut(el.dataset.id);
      if(el.matches('.btn-invoice')) sendInvoice(el.dataset.id);
    });

    document.getElementById('printAllInvoices').addEventListener('click', ()=>{
      alert('In tất cả hóa đơn (mô phỏng)');
    });
    document.getElementById('emailAllInvoices').addEventListener('click', ()=>{
      alert('Gửi email tất cả hóa đơn (mô phỏng)');
    });
    document.getElementById('refreshStatus').addEventListener('click', ()=>{
      renderRooms(); renderBookings();
      detailsContent.innerHTML = '<p>Đã làm mới trạng thái phòng.</p>';
    });
  }

  // init
  function init(){
    renderBookings();
    renderRooms();
    renderRoomTypesSidebar();
    renderKPIs();
    renderCustomers();
    attachSidebarMenuHandlers();
    // default: make 'Room types' active and show rooms panel
    const defaultMenu = document.querySelector('.menu-item[data-panel="roomTypes"]');
    if(defaultMenu) defaultMenu.classList.add('active');
    switchMainPanel('rooms');
    // render staff list so edit is ready
    renderStaffList();
    attachActionHandlers();
  }

  function attachSidebarMenuHandlers(){
    // menu switching
    document.querySelectorAll('.menu-item').forEach(it=>{
      it.addEventListener('click', ()=>{
        document.querySelectorAll('.menu-item').forEach(m=>m.classList.remove('active'));
        it.classList.add('active');
        const panel = it.dataset.panel;
        // DO NOT toggle/hide sidebar sections — sidebar stays static.
        // Only switch the main content panels.
        if(panel==='roomTypes') switchMainPanel('rooms');
        else if(panel==='staffPanel') { switchMainPanel('staff'); renderStaffList(); }
        else if(panel==='customersPanel') { switchMainPanel('customers'); renderCustomers(); }
      });
    });

    // customer search (sidebar)
    const cs = document.getElementById('customerSearch');
    if(cs) cs.addEventListener('input', ()=>{
      const q = cs.value.trim().toLowerCase();
      if(!q) return renderCustomers();
      const filtered = customers.filter(c=> c.name.toLowerCase().includes(q) || c.id.toLowerCase().includes(q) );
      renderCustomers(filtered);
    });
    // main panel customer search
    const mcs = document.getElementById('mainCustomerSearch');
    if(mcs) mcs.addEventListener('input', ()=>{
      const q = mcs.value.trim().toLowerCase();
      if(!q) return renderCustomers();
      const filtered = customers.filter(c=> c.name.toLowerCase().includes(q) || c.id.toLowerCase().includes(q) );
      renderCustomers(filtered);
    });
    // staff form handlers
    const saveBtn = document.getElementById('saveStaff'); if(saveBtn) saveBtn.addEventListener('click', saveStaffFromForm);
    const cancelBtn = document.getElementById('cancelStaff'); if(cancelBtn) cancelBtn.addEventListener('click', cancelStaffEdit);
  }

  // Wait DOM
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', init);
  else init();

})();
