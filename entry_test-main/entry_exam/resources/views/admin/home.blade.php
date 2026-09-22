<!-- base view -->
@extends('common.admin.base')

<!-- main contents -->
@section('main_contents')
    <div class="page-wrapper">
        <div class="admin-card" style="max-width: 860px;">
            <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 8px; letter-spacing: -0.02em;">
                Dear THK Holdings Vietnam in Hanoi,
            </h2>
            <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 24px;">
                Thank you for reviewing my entrance examination. Below is the concise summary of all features I have implemented and updated according to the exam requirements:
            </p>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 24px;">

            <!-- Required Features -->
            <h3 style="font-size: 18px; font-weight: 700; color: #2563eb; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>✅</span> Required Features
            </h3>

            <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">1. Hotel Search (Bug Fix & Enhancements)</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li><strong>Bug Fix:</strong> Fixed the error when submitting an empty form. Displays <code>"何も入力されていません"</code> in red directly below the form.</li>
                        <li><strong>Partial Match:</strong> Modified hotel name search from exact match to partial match (<code>LIKE %keyword%</code>).</li>
                        <li><strong>Prefecture Filter:</strong> Added a select dropdown box to search and filter hotels by prefecture.</li>
                    </ul>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">2. Hotel Creation</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li>Implemented hotel creation form with proper validation (hotel name, prefecture).</li>
                        <li>Supported image uploads stored under <code>public/assets/img/hotel/</code>.</li>
                    </ul>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">3. Hotel Information Edit (3-Step Page Transition)</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li><strong>Step 1 (Input):</strong> Edit form with current information and image preview.</li>
                        <li><strong>Step 2 (Confirmation):</strong> Review screen showing edited content before updating.</li>
                        <li><strong>Step 3 (Completion):</strong> Completion notice with a link back to hotel search.</li>
                    </ul>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">4. Hotel Deletion</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li>Implemented JavaScript confirmation popup (<code>confirm('本当に削除しますか？')</code>) before executing deletion.</li>
                    </ul>
                </div>
            </div>

            <!-- Optional Features -->
            <h3 style="font-size: 18px; font-weight: 700; color: #7c3aed; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>🌟</span> Optional Features (Extra Points)
            </h3>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div style="background: #faf5ff; border: 1px solid #ede9fe; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">1. Hotel Booking Feature</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li>Created <code>bookings</code> table migration and seeded sample test records.</li>
                        <li>Added <strong>"予約情報検索"</strong> link in the admin side menu.</li>
                        <li>Created booking search and results screen (customer name, contact, check-in, check-out).</li>
                    </ul>
                </div>

                <div style="background: #faf5ff; border: 1px solid #ede9fe; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">2. Modern UI & Responsive Design</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li>Redesigned the interface with modern card tables, styled select boxes, and clean typography.</li>
                        <li>Ensured full responsiveness across mobile, tablet, and desktop screens.</li>
                    </ul>
                </div>

                <div style="background: #faf5ff; border: 1px solid #ede9fe; border-radius: 8px; padding: 16px 20px;">
                    <strong style="color: #0f172a; font-size: 15px;">3. Pagination & Filter Persistence</strong>
                    <ul style="margin: 8px 0 0 18px; padding: 0; list-style: disc; color: #475569; font-size: 14px; line-height: 1.6;">
                        <li>Implemented 10-items-per-page pagination with previous/next controls, page numbers, and total item range indicator.</li>
                        <li>Auto-displays existing records upon page load and maintains search query parameters across pagination links.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection