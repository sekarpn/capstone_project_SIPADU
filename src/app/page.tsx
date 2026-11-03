'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/context/AuthContext';

export default function HomePage() {
  const { user, loading } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (loading) return;
    if (!user) {
      router.replace('/login');
    } else if (user.role === 'admin') {
      router.replace('/admin/dashboard');
    } else {
      router.replace('/user/dashboard');
    }
  }, [user, loading, router]);

  return (
    <div className="flex items-center justify-center h-screen">
      <p className="text-gray-500">Memuat...</p>
    </div>
  );
}
