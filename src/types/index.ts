export type BookingStatus = 'pending' | 'approved' | 'rejected' | 'cancelled';
export type UserRole = 'admin' | 'user';

export interface User {
  id: string;
  name: string;
  email: string;
  password: string;
  role: UserRole;
  organization: string;
}

export interface AuthUser {
  id: string;
  name: string;
  email: string;
  role: UserRole;
  organization: string;
}

export interface Room {
  id: string;
  name: string;
  capacity: number;
  location: string;
  facilities: string[];
  isActive: boolean;
}

export interface Booking {
  id: string;
  roomId: string;
  roomName: string;
  userId: string;
  userName: string;
  organization: string;
  date: string;
  startTime: string;
  endTime: string;
  purpose: string;
  status: BookingStatus;
}
