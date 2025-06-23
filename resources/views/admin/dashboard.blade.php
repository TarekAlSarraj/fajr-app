<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            لوحة التحكم - تقارير المستخدمين
        </h2>
    </x-slot>

    <div class="p-4 max-w-7xl mx-auto space-y-12">

    <!-- Leaderboard -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">🏆 لوحة الشرف - المتصدرين</h3>
        <table id="leaderboardTable" class="min-w-full text-sm text-gray-500 dark:text-gray-300 rtl:text-right">
            <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">الاسم</th>
                    <th class="px-4 py-3">الحضور الكلّي 📅</th>
                    <th class="px-4 py-3">السلسلة الحالية 🔥</th>
                    <th class="px-4 py-3">الجواهر 💎</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $index => $user)
                    <tr class="text-center border-b dark:border-gray-700">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->total_attendance_days }} 📅</td>
                        <td class="px-4 py-2">{{ $user->current_streak }} 🔥</td>
                        <td class="px-4 py-2">{{ $user->gems }} 💎</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

<br>
        <!-- Attendance Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">📅 سجلات الحضور والتسميع</h3>
            <table id="attendanceTable" class="min-w-full text-sm text-gray-500 dark:text-gray-300 rtl:text-right">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">نوع الموعد</th>
                        <th class="px-4 py-3">التاريخ</th>
                        <th class="px-4 py-3">التسميع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="text-center border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $attendance->user->name }}</td>
                            <td class="px-4 py-2">{{ $attendance->event->name }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($attendance->attended_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                @forelse($attendance->recitations as $recitation)
                                    <div class="mb-1">📖 {{ $recitation->surah_name }} ({{ $recitation->from_verse }}-{{ $recitation->to_verse }})</div>
                                @empty
                                    <span class="text-gray-400 dark:text-gray-500">لا يوجد</span>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">لا توجد سجلات حضور حتى الآن</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
<br>
        <!-- Challenges Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">🎯 تحديات المستخدمين</h3>
            <table id="challengesTable" class="min-w-full text-sm text-gray-500 dark:text-gray-300 rtl:text-right">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">الاسم</th>
                        <th class="px-4 py-3">التحدّي</th>
                        <th class="px-4 py-3">الجواهر </th>
                        <th class="px-4 py-3">حالة الموافقة</th>
                        <th class="px-4 py-3">مكتمل في</th>
                        <th class="px-4 py-3">مقفل حتى</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($userChallenges as $userChallenge)
                        <tr class="text-center border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $userChallenge->user->name }}</td>
                            <td class="px-4 py-2">{{ $userChallenge->challenge->title }}</td>
                            <td class="px-4 py-2 text-indigo-600 dark:text-indigo-400 font-semibold">{{ $userChallenge->challenge->reward_gems }} 💎</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($userChallenge->approved)
                                    <span class="text-green-600 dark:text-green-400 font-semibold">موافق عليه ✅</span>
                                @else
                                    <form action="{{ route('admin.challenges.approve', $userChallenge) }}" method="POST" onsubmit="return confirm('تأكيد الموافقة؟')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded bg-green-600 text-white text-xs hover:bg-green-700">تأكيد الموافقة؟</button>
                                    </form>
                                @endif
                            </td>

                            <td class="px-4 py-2">{{ $userChallenge->completed_at ? $userChallenge->completed_at->format('Y-m-d H:i') : '-' }}</td>
                            <td class="px-4 py-2">{{ $userChallenge->locked_until ? $userChallenge->locked_until->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">لا توجد تحديات مستخدمة حتى الآن</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    <br>
        <!-- Purchases Table -->
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">🛒 المشتريات</h3>
            <table id="purchasesTable" class="min-w-full text-sm text-gray-500 dark:text-gray-300 rtl:text-right">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-4 py-3">المستخدم</th>
                        <th class="px-4 py-3">العنصر</th>
                        <th class="px-4 py-3">السعر</th>
                        <th class="px-4 py-3">التاريخ</th>
                        <th class="px-4 py-3">مقفل حتى</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr class="text-center border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $purchase->user->name }}</td>
                            <td class="px-4 py-2">{{ $purchase->item->name }}</td>
                            <td class="px-4 py-2 text-indigo-600 dark:text-indigo-400 font-semibold">{{ $purchase->item->price_in_gems }} 💎</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($purchase->created_at)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">{{ $purchase->locked_until ? $purchase->locked_until->format('Y-m-d') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">لا توجد مشتريات حتى الآن</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        

    </div>

    @push('scripts')
        <!-- DataTables -->
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet" />
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#leaderboardTable').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' },
                    responsive: true,
                    pageLength: 10,
                    order: [[2, 'desc']], // Sort by "عدد الأيام" DESC
                });

                $('#attendanceTable').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' },
                    responsive: true,
                    pageLength: 10,
                });
                $('#challengesTable').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' },
                    responsive: true,
                    pageLength: 10,
                });
                $('#purchasesTable').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json' },
                    responsive: true,
                    pageLength: 10,
                });
            });
        </script>
    @endpush
</x-app-layout>
