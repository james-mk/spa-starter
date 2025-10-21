<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Traits\LogsActivity;

class SettingController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $settings = Setting::first();

        if (!$settings) {
            $settings = Setting::create([
                'company_name' => '',
                'theme' => 'light',
                'primary_color' => '#828282',
                'secondary_color' => '#1e448f',
            ]);
        }

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'theme' => 'required|in:light,dark',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'timezone' => 'nullable|string|max:100',
            'date_format' => 'nullable|string|max:50',
            'time_format' => 'nullable|string|max:50',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $settings = Setting::first();

        if (!$settings) {
            $settings = new Setting();
        }

        // Store old values
        $oldData = [
            'company_name' => $settings->company_name,
            'theme' => $settings->theme,
            'primary_color' => $settings->primary_color,
            'secondary_color' => $settings->secondary_color,
        ];

        if ($request->hasFile('company_logo')) {
            if ($settings->company_logo && file_exists(public_path($settings->company_logo))) {
                unlink(public_path($settings->company_logo));
            }

            $logo = $request->file('company_logo');
            $logoName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
            $uploadPath = public_path('uploads/company');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $logo->move($uploadPath, $logoName);
            $data['company_logo'] = 'uploads/company/' . $logoName;
        }

        $settings->fill($data);
        $settings->save();

        // Log activity
        $this->logActivity(
            'updated',
            Setting::class,
            $settings->id,
            "Updated system settings",
            $oldData,
            [
                'company_name' => $settings->company_name,
                'theme' => $settings->theme,
                'primary_color' => $settings->primary_color,
                'secondary_color' => $settings->secondary_color,
            ]
        );

        return response()->json($settings);
    }

    public function publicSettings()
    {
        $settings = Setting::first();

        if (!$settings) {
            $settings = Setting::create([
                'company_name' => 'Fleet Management System',
                'theme' => 'light',
                'primary_color' => '#1976d2',
                'secondary_color' => '#1e448f',
            ]);
        }

        return response()->json($settings);
    }
}
