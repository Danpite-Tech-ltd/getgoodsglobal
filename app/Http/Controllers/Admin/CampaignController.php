<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CampaignReview;
use App\Models\Campaign;
use App\Models\LandingBanner;
use Image;
use Toastr;
use Str;
use File;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $show_data = Campaign::orderBy('id', 'DESC')->get();
        return view('backEnd.campaign.index', compact('show_data'));
    }
    public function create()
    {
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.campaign.create', compact('products'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $input = $request->except(['files', 'banner_img', 'campaign_review', 'image']);

        $uploadPath = 'public/uploads/campaign/';
        $savePath = public_path('uploads/campaign/');

        if (!File::exists($savePath)) {
            File::makeDirectory($savePath, 0755, true);
        }

        // Single images
        foreach (['banner', 'image_one', 'image_two', 'image_three'] as $field) {
            if ($request->hasFile($field)) {
                $image = $request->file($field);
                if ($image && $image->isValid()) {
                    $name = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image->getClientOriginalName()));
                    $image->move($savePath, $name);
                    $input[$field] = $uploadPath . $name;
                }
            }
        }

        $input['slug'] = strtolower(Str::slug($request->name));
        $campaign = Campaign::create($input);

        // Main campaign image (optional single)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image && $image->isValid()) {
                $name = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image->getClientOriginalName()));
                $image->move($savePath, $name);

                $pimage = new CampaignReview();
                $pimage->campaign_id = $campaign->id;
                $pimage->image = $uploadPath . $name;
                $pimage->save();
            }
        }

        // Multiple banner images
        if ($request->hasFile('banner_img')) {
            foreach ($request->file('banner_img') as $banner_image) {
                if ($banner_image && $banner_image->isValid()) {
                    $imageName = 'campaign_banner' . uniqid() . '.' . $banner_image->getClientOriginalExtension();
                    $banner_image->move($savePath, $imageName);

                    $campaign_banner = new LandingBanner();
                    $campaign_banner->campaign_id = $campaign->id;
                    $campaign_banner->image = $uploadPath . $imageName;
                    $campaign_banner->save();
                }
            }
        }

        // Multiple review images
        if ($request->hasFile('campaign_review')) {
            foreach ($request->file('campaign_review') as $review_image) {
                if ($review_image && $review_image->isValid()) {
                    $imageName = 'campaign_review' . uniqid() . '.' . $review_image->getClientOriginalExtension();
                    $review_image->move($savePath, $imageName);

                    $campaign_review = new CampaignReview();
                    $campaign_review->campaign_id = $campaign->id;
                    $campaign_review->image = $uploadPath . $imageName;
                    $campaign_review->save();
                }
            }
        }

        Toastr::success('Success', 'Data inserted successfully');
        return redirect()->route('campaign.index');
    }

    public function edit($id)
    {
        $edit_data = Campaign::with('images')->find($id);
        $select_products = Product::where('campaign_id', $id)->get();
        $show_data = Campaign::orderBy('id', 'DESC')->get();
        $products = Product::where(['status' => 1])->select('id', 'name', 'status')->get();
        return view('backEnd.campaign.edit', compact('edit_data', 'products', 'select_products'));
    }

    public function update(Request $request)
    {
        // return $request;
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);

        $update_data = Campaign::find($request->hidden_id);
        $input = $request->except('hidden_id', 'product_ids', 'files', 'image');
        $image_one = $request->file('image_one');

        // banner
        if ($request->hasFile('banner')) {
            // Delete the old image if it exists
            if ($update_data->banner && file_exists($update_data->banner)) {
                unlink($update_data->banner); // Remove the old image
            }

            // Upload and store the new image
            $banner = $request->file('banner');
            $namebanner = time() . '-' . strtolower(preg_replace('/\s+/', '-', $banner->getClientOriginalName()));
            $uploadPathbanner = 'uploads/campaign/';
            $banner->move($uploadPathbanner, $namebanner); // Save to the 'uploads/campaign/' folder directly
            $input['banner'] = $uploadPathbanner . $namebanner; // Add the new image path to the input array
        }

        // Image One
        if ($request->hasFile('image_one')) {
            // Delete the old image if it exists
            if ($update_data->image_one && file_exists($update_data->image_one)) {
                unlink($update_data->image_one); // Remove the old image
            }

            // Upload and store the new image
            $image_one = $request->file('image_one');
            $name1 = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image_one->getClientOriginalName()));
            $uploadPath1 = 'uploads/campaign/';
            $image_one->move($uploadPath1, $name1); // Save to the 'uploads/campaign/' folder directly
            $input['image_one'] = $uploadPath1 . $name1; // Add the new image path to the input array
        }

        // Image Two
        if ($request->hasFile('image_two')) {
            // Delete the old image if it exists
            if ($update_data->image_two && file_exists($update_data->image_two)) {
                unlink($update_data->image_two); // Remove the old image
            }

            // Upload and store the new image
            $image_two = $request->file('image_two');
            $name2 = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image_two->getClientOriginalName()));
            $uploadPath2 = 'uploads/campaign/';
            $image_two->move($uploadPath2, $name2); // Save to the 'uploads/campaign/' folder directly
            $input['image_two'] = $uploadPath2 . $name2; // Add the new image path to the input array
        }

        // Image Three
        if ($request->hasFile('image_three')) {
            // Delete the old image if it exists
            if ($update_data->image_three && file_exists($update_data->image_three)) {
                unlink($update_data->image_three); // Remove the old image
            }

            // Upload and store the new image
            $image_three = $request->file('image_three');
            $name3 = time() . '-' . strtolower(preg_replace('/\s+/', '-', $image_three->getClientOriginalName()));
            $uploadPath3 = 'uploads/campaign/';
            $image_three->move($uploadPath3, $name3); // Save to the 'uploads/campaign/' folder directly
            $input['image_three'] = $uploadPath3 . $name3; // Add the new image path to the input array
        }



        if ($request->hasFile('image')) {
            $image_four = $request->file('image');

            $name =  time() . '-' . $image_four->getClientOriginalName();
            $name = strtolower(preg_replace('/\s+/', '-', $name));
            $uploadPath = 'public/uploads/campaign/';
            $image_four->move($uploadPath, $name);
            $imageUrl = $uploadPath . $name;



            $input['image'] = $imageUrl;
        }
        // image four
        $input['slug'] = strtolower(Str::slug($request->name));
        $update_data = Campaign::find($request->hidden_id);
        $update_data->update($input);

        // banner
        if ($request->hasFile('banner_img')) {

            $oldBanners = LandingBanner::where('campaign_id', $update_data->id)->get();
            foreach ($oldBanners as $old) {
                $oldImagePath = public_path($old->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $old->delete();
            }

            $relativePath = 'public/uploads/campaign/';
            $savePath = public_path('uploads/campaign/');

            if (!File::exists($savePath)) {
                File::makeDirectory($savePath, 0755, true);
            }

            foreach ($request->banner_img as $banner_image) {
                $imageName = 'campaign_banner' . uniqid() . '.' . $banner_image->getClientOriginalExtension();
                $banner_image->move($savePath, $imageName);

                $campaign_banner = new LandingBanner();
                $campaign_banner->campaign_id = $update_data->id;
                $campaign_banner->image = $relativePath . $imageName;
                $campaign_banner->save();
            }
        }

        // campaign review

        if ($request->hasFile('campaign_review')) {

            $oldReviews = CampaignReview::where('campaign_id', $update_data->id)->get();
            foreach ($oldReviews as $old) {
                $oldImagePath = public_path($old->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $old->delete();
            }

            $relativePath = 'public/uploads/campaign/';
            $savePath = public_path('uploads/campaign/');

            if (!File::exists($savePath)) {
                File::makeDirectory($savePath, 0755, true);
            }

            foreach ($request->campaign_review as $review_image) {
                $imageName = 'campaign_review' . uniqid() . '.' . $review_image->getClientOriginalExtension();
                $review_image->move($savePath, $imageName);

                $campaign_review = new CampaignReview();
                $campaign_review->campaign_id = $update_data->id;
                $campaign_review->image = $relativePath . $imageName;
                $campaign_review->save();
            }
        }


        Toastr::success('Success', 'Data update successfully');
        return redirect()->route('campaign.index');
    }

    public function inactive(Request $request)
    {
        $inactive = Campaign::find($request->hidden_id);
        $inactive->status = 0;
        $inactive->save();
        Toastr::success('Success', 'Data inactive successfully');
        return redirect()->back();
    }
    public function active(Request $request)
    {
        $active = Campaign::find($request->hidden_id);
        $active->status = 1;
        $active->save();
        Toastr::success('Success', 'Data active successfully');
        return redirect()->back();
    }
    public function destroy(Request $request)
    {

        $delete_data = Campaign::find($request->hidden_id);
        $delete_data->delete();

        $campaign = Product::whereNotNull('campaign_id')->get();
        foreach ($campaign as $key => $value) {
            $product = Product::find($value->id);
            $product->campaign_id = null;
            $product->save();
        }
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
    public function imgdestroy(Request $request)
    {
        $delete_data = CampaignReview::find($request->id);
        File::delete($delete_data->image);
        $delete_data->delete();
        Toastr::success('Success', 'Data delete successfully');
        return redirect()->back();
    }
}
