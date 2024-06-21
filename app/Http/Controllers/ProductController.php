<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $products = DB::table('products')->get();
        return response()->json($products);
    }

    public function getProductById($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        return response()->json($product);
    }

    public function getProductNameById($id)
    {
        $productName = DB::table('products')->where('id', $id)->value('name');
        return response()->json(['name' => $productName]);
    }

    public function getProductNames()
    {
        $productNames = DB::table('products')->pluck('name');
        return response()->json($productNames);
    }

    public function insertSingleProduct()
    {
        DB::table('products')->insert([
            'name' => 'Product A',
            'description' => 'Description of Product A',
            'price' => 19.99,
            'quantity' => 100
        ]);

        return response()->json(['message' => 'Product A inserted']);
    }

    public function insertMultipleProducts()
    {
        DB::table('products')->insert([
            ['name' => 'Product B', 'description' => 'Description of Product B', 'price' => 29.99, 'quantity' => 200],
            ['name' => 'Product C', 'description' => 'Description of Product C', 'price' => 39.99, 'quantity' => 300]
        ]);

        return response()->json(['message' => 'Multiple products inserted']);
    }

    public function updateSingleProduct($id)
    {
        DB::table('products')
            ->where('id', $id)
            ->update(['name' => 'Updated Product A']);

        return response()->json(['message' => 'Product updated']);
    }

    public function updateMultipleProducts()
    {
        DB::table('products')
            ->where('quantity', '<', 50)
            ->update(['quantity' => 50]);

        return response()->json(['message' => 'Multiple products updated']);
    }

    public function deleteSingleProduct($id)
    {
        DB::table('products')->where('id', $id)->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function deleteMultipleProducts()
    {
        DB::table('products')->where('quantity', '<', 10)->delete();
        return response()->json(['message' => 'Multiple products deleted']);
    }

    public function joinQueries()
    {
        $products = DB::table('products')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('products.*', 'order_items.order_id')
            ->get();

        return response()->json($products);
    }

    public function groupByHaving()
    {
        $inventory = DB::table('products')
            ->select(DB::raw('sum(quantity) as total_quantity, price'))
            ->groupBy('price')
            ->having('total_quantity', '>', 100)
            ->get();

        return response()->json($inventory);
    }
}

