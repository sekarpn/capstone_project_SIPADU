import { User, Room, Booking } from '@/types';

export const MOCK_USERS: User[] = [
  { id: '1', name: 'Admin Sistem', email: 'admin@test.com', password: 'admin123', role: 'admin', organization: 'UPT TI' },
  { id: '2', name: 'Budi Santoso', email: 'user@test.com', password: 'user123', role: 'user', organization: 'BEM UPNVJ' },
  { id: '3', name: 'Siti Rahma', email: 'siti@test.com', password: 'user123', role: 'user', organization: 'BEM FT' },
  { id: '4', name: 'Ahmad Fauzi', email: 'ahmad@test.com', password: 'user123', role: 'user', organization: 'HIMAIKOM' },
  { id: '5', name: 'Dewi Kusuma', email: 'dewi@test.com', password: 'user123', role: 'user', organization: 'MPM UPNVJ' },
];

export const MOCK_ROOMS: Room[] = [
  { id: '1', name: 'MERCe', capacity: 200, location: 'Gedung Rektorat Lt. 1', facilities: ['AC', 'Proyektor', 'Sound System', 'WiFi'], isActive: true },
  { id: '2', name: 'Tanah Airku', capacity: 150, location: 'Gedung FT Lt. 2', facilities: ['AC', 'Proyektor', 'Microphone'], isActive: true },
  { id: '3', name: 'Dr. Wahidin Sudiro Husodo', capacity: 300, location: 'Gedung Utama Lt. 1', facilities: ['AC', 'Proyektor', 'Sound System', 'Panggung'], isActive: true },
  { id: '4', name: 'Auditorium FISIP', capacity: 100, location: 'Gedung FISIP Lt. 3', facilities: ['AC', 'Proyektor', 'Microphone', 'WiFi'], isActive: true },
  { id: '5', name: 'Aula Utama', capacity: 500, location: 'Gedung Serba Guna', facilities: ['AC', 'Proyektor', 'Sound System', 'WiFi', 'Panggung'], isActive: false },
];

export const MOCK_BOOKINGS: Booking[] = [
  {
    id: '1', roomId: '1', roomName: 'MERCe',
    userId: '2', userName: 'Budi Santoso', organization: 'BEM UPNVJ',
    date: '2026-09-16', startTime: '12.00', endTime: '17.00',
    purpose: 'Rapat koordinasi BEM', status: 'pending',
  },
  {
    id: '2', roomId: '2', roomName: 'Tanah Airku',
    userId: '3', userName: 'Siti Rahma', organization: 'BEM FT',
    date: '2026-09-15', startTime: '12.00', endTime: '17.00',
    purpose: 'Seminar nasional teknik', status: 'approved',
  },
  {
    id: '3', roomId: '1', roomName: 'MERCe',
    userId: '5', userName: 'Dewi Kusuma', organization: 'MPM UPNVJ',
    date: '2026-09-15', startTime: '12.00', endTime: '17.00',
    purpose: 'Sidang MPM', status: 'rejected',
  },
  {
    id: '4', roomId: '3', roomName: 'Dr. Wahidin Sudiro Husodo',
    userId: '2', userName: 'Budi Santoso', organization: 'BEMF-IK',
    date: '2026-09-14', startTime: '07.00', endTime: '16.00',
    purpose: 'Launching aplikasi layanan terpadu', status: 'approved',
  },
  {
    id: '5', roomId: '4', roomName: 'Auditorium FISIP',
    userId: '4', userName: 'Ahmad Fauzi', organization: 'HIMAIKOM',
    date: '2026-09-11', startTime: '12.00', endTime: '17.00',
    purpose: 'Workshop pemrograman web', status: 'cancelled',
  },
];

export const CHART_DATA = [
  { month: 'Apr', bookings: 75 },
  { month: 'Mei', bookings: 50 },
  { month: 'Jun', bookings: 15 },
  { month: 'Jul', bookings: 5 },
  { month: 'Agu', bookings: 35 },
  { month: 'Sep', bookings: 20 },
];
