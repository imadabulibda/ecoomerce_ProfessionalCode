<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainCategoriesController extends Controller
{

    public function index()
    {
        $categories = MainCategory::where('translation_lang', get_default_lang())
            ->selection()->get();
        return view('admin.maincategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }


    public function store(MainCategoryRequest $request)
    {
        try {
            $main_categories = collect($request->category);

            // retrieve default category (ar - in this case)
            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_category = array_values($filter->all()) [0];

            $filePath = "";
            if ($request->has('photo'))
                $filePath = uploadImage('maincategories', $request->photo);

            // start transaction
            DB::beginTransaction();
            // insert default category
            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath
            ]);

            // all other languages except the default
            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });

            if (isset($categories) && $categories->count()) {
                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath
                    ];
                }
                // insert other categories
                MainCategory::insert($categories_arr);
            }
            // end transaction
            DB::commit();
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (\Exception $ex) {
            dd($ex);
            DB::rollback();
            if (file_exists($filePath)) unlink($filePath);
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function edit($mainCat_id)
    {
        //get specific categories and its translations
        $mainCategory = MainCategory::with('categories')
            ->selection()
            ->find($mainCat_id);

        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
        return view('admin.maincategories.edit', compact('mainCategory'));
    }

    public function update($mainCat_id, MainCategoryRequest $request)
    {
        try {
            $main_category = MainCategory::find($mainCat_id);
            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
            // update date
            $category = array_values($request->category) [0];
            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $mainCat_id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);
            // save image
            if ($request->has('photo')) {
                $filePath = uploadImage('maincategories', $request->photo);
                //todo : delete the old photo
                MainCategory::where('id', $mainCat_id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }
            return redirect()->route('admin.maincategories')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function destroy($id)
    {
        try {
            $mainCategory = MainCategory::find($id);
            if (!$mainCategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            $mainCategoryVendors = $mainCategory->vendors();
            if (isset($mainCategoryVendors) && $mainCategoryVendors->count() > 0)// it has vendors and more than 0
                return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف هذا القسم']);


            // delete the photo from files
            $relative_path = Str::after($mainCategory->photo, 'assets/');
            $relative_path = base_path('assets/' . $relative_path);
            unlink($relative_path);

            // now delete mainCategory
            $mainCategory->delete();

            // delete all the translations of it.


            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (\Exception $exception) {
            return $exception;
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function changeStatus($id)
    {
        try {
            $mainCategory = MainCategory::find($id);
            if (!$mainCategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود']);

            $status = $mainCategory->active == 0 ? 1 : 0; // flip the value of active
            $mainCategory->update(['active' => $status]);

            return redirect()->route('admin.maincategories')->with(['success' => 'تم تغيير حالة القسم بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
