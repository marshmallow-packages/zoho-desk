<?php

namespace Marshmallow\ZohoDesk\Resources;

use Marshmallow\ZohoDesk\Facades\ZohoDesk;
use Marshmallow\ZohoDesk\Models\ZohoProduct as ProductModel;

class ZohoProduct
{
    public function get()
    {
    }

    public function findOrCreate(string $name, array $extra_data = []): ProductModel
    {
        $product_exists = $this->findOneByName($name);
        if ($product_exists) {
            if (in_array(config('zohodesk.department_id'), $product_exists->departmentIds)) {
                return $product_exists;
            }
            $departmentIds = array_merge($product_exists->departmentIds, [
                config('zohodesk.department_id')
            ]);

            ZohoDesk::patch("/products/{$product_exists->id}", [
                'departmentIds' => $departmentIds,
            ]);

            return $this->findOneByName($name);
        }

        /*
         * Contact doesn't exist yet so lets create it.
         */
        return $this->create(array_merge($extra_data, [
            'productName' => $name,
            'departmentIds' => [
                config('zohodesk.department_id'),
            ],
        ]));
    }

    public function findOneByName($name): ?ProductModel
    {
        $product_exists = $this->search([
            'productName' => $name,
        ]);
        if ($product_exists->count()) {
            return ProductModel::make($product_exists->first());
        }

        return null;
    }

    public function list()
    {
        return ZohoDesk::get('/products');
    }

    public function create(array $data)
    {
        $product = ZohoDesk::post('/products', $data);

        return ProductModel::make($product);
    }

    public function search(array $data)
    {
        return ZohoDesk::get('/products/search?'.http_build_query($data));
    }

    public function update(int $contact_id, array $data)
    {
    }

    public function profiles()
    {
    }

    public function listByIds()
    {
    }

    public function tickets()
    {
    }

    public function products()
    {
    }

    public function count()
    {
    }

    public function statistics()
    {
    }

    public function merge()
    {
    }

    public function markAsSpam()
    {
    }

    public function associateProducts()
    {
    }

    public function history()
    {
    }

    public function inviteAsEndUser()
    {
    }

    public function inviteMultipleAsEndUser()
    {
    }

    public function helpCenters()
    {
    }
}
