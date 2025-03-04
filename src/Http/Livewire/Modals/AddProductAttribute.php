<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Database\Eloquent\Collection;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;
use Shopper\Framework\Models\Shop\Product\ProductAttributeValue;
use WireUi\Traits\Actions;

class AddProductAttribute extends ModalComponent
{
    use Actions;

    public int $productId;
    public string $type = 'text';
    public array $attributes;
    public ?int $attribute_id = null;
    public array $multipleValues = [];
    public Collection $values;
    public ?string $value = null;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate($value)
    {
        $this->value = $value;
    }

    public function mount(int $productId, array $attributes)
    {
        $this->productId = $productId;
        $this->attributes = $attributes;
    }

    public function save()
    {
        if ($this->type === 'checkbox' || $this->type === 'colorpicker') {
            $this->validate(['multipleValues' => 'required|array']);
        } else {
            $this->validate(['value' => 'required', 'attribute_id' => 'required|int']);
        }

        $productAttribute = ProductAttribute::query()->create([
            'product_id' => $this->productId,
            'attribute_id' => $this->attribute_id,
        ]);

        if ($this->type === 'checkbox' || $this->type === 'colorpicker') {
            foreach ($this->multipleValues as $checkboxValue) {
                ProductAttributeValue::query()->create([
                    'attribute_value_id' => $checkboxValue,
                    'product_attribute_id' => $productAttribute->id,
                ]);
            }
        } else {
            ProductAttributeValue::query()->create([
                'attribute_value_id' => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? null
                    : $this->value,
                'product_attribute_id' => $productAttribute->id,
                'product_custom_value' => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? $this->value
                    : null,
            ]);
        }

        $this->notification()->success(__('Attribute Added'), __('You have successfully added an attribute to this product!'));

        $this->emit('onProductAttributeAdded');

        $this->closeModal();
    }

    public function updatedAttributeId(string $value)
    {
        if ($value === '0') {
            return;
        }

        $attribute = Attribute::query()->with('values')->find($value);
        $this->type = $attribute->type;
        $this->value = '';

        if ($attribute->values->isNotEmpty()) {
            $this->values = $attribute->values;
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render()
    {
        return view('shopper::livewire.modals.add-product-attribute');
    }
}
