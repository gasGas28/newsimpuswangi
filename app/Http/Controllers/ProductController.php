<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request; // ✅ penting ini
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'products' => $products,
            'userRole' => $user->role
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Product::create($validated);

        return redirect('/dashboard');
    }


public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return Inertia::location(route('dashboard'));
}





}
