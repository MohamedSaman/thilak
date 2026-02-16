<?php

namespace App\Livewire\Admin;

use Exception;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Cheque;
use Livewire\Component;
use App\Models\Customer;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use App\Models\AdminSale;
use App\Models\ProductDetail;
use App\Models\SalesItem;
use App\Models\ProductCategory;

#[Title("Store Billing")]
#[Layout('components.layouts.blank')]
class StoreBilling extends Component
{
    use WithFileUploads;

    public $search = '';
    public $searchResults = [];
    public $selectedCategory = null;
    public $categories = [];
    public $cart = [];
    public $quantities = [];
    public $discounts = [];
    public $prices = [];
    public $quantityTypes = [];
    public $productDetails = null;
    public $subtotal = 0;
    public $totalDiscount = 0;
    public $grandTotal = 0;

    // Overall Discount Properties
    public $discountType = 'percentage'; // Default to percentage
    public $discountValue = 0;
    public $calculatedDiscount = 0;

    public $customers = [];
    public $customerId = null;
    public $customerType = 'wholesale';

    public $newCustomerName = '';
    public $newCustomerPhone = '';
    public $newCustomerEmail = '';
    public $newCustomerType = 'wholesale';
    public $newCustomerAddress = '';
    public $newCustomerNotes = '';

    public $saleNotes = '';
    public $paymentType = 'partial';
    public $paymentMethod = '';
    public $paymentReceiptImage;
    public $paymentReceiptImagePreview = null;
    public $bankName = '';

    public $initialPaymentAmount = 0;
    public $initialPaymentMethod = '';
    public $initialPaymentReceiptImage;
    public $initialPaymentReceiptImagePreview = null;
    public $initialBankName = '';

    public $balanceAmount = 0;
    public $balancePaymentMethod = '';
    public $balanceDueDate = '';
    public $balancePaymentReceiptImage;
    public $balancePaymentReceiptImagePreview = null;
    public $balanceBankName = '';

    public $lastSaleId = null;
    public $showReceipt = false;
    public $receipt = null;

    public $cashAmount = 0;
    public $cheques = [];
    public $newCheque = [
        'number' => '',
        'bank' => '',
        'date' => '',
        'amount' => '',
    ];
    public $duePaymentMethod = '';
    public $duePaymentAttachment;
    public $duePaymentAttachmentPreview = null;

    public $banks = [];

    public $editingSaleId = null;
    public $currentInvoiceNumber = '';

    protected $listeners = ['quantityUpdated' => 'updateTotals'];

    public function mount()
    {
        $this->loadCustomers();
        $this->loadBanks();
        $this->loadCategories();
        $this->loadAllProducts();
        $this->updateTotals();
        $this->currentInvoiceNumber = Sale::generateInvoiceNumber();
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));

        // Check if editing an existing sale
        if (session()->has('editSaleId')) {
            $saleId = session('editSaleId');
            $this->loadSaleForEdit($saleId);
            session()->forget('editSaleId');
        }
    }

    public function loadBanks()
    {
        $this->banks = [
            'Bank of Ceylon (BOC)',
            'Commercial Bank of Ceylon (ComBank)',
            'Hatton National Bank (HNB)',
            'People\'s Bank',
            'Sampath Bank',
            'National Development Bank (NDB)',
            'DFCC Bank',
            'Nations Trust Bank (NTB)',
            'Seylan Bank',
            'Amana Bank',
            'Cargills Bank',
            'Pan Asia Banking Corporation',
            'Union Bank of Colombo',
            'Bank of China Ltd',
            'Citibank, N.A.',
            'Habib Bank Ltd',
            'Indian Bank',
            'Indian Overseas Bank',
            'MCB Bank Ltd',
            'Public Bank Berhad',
            'Standard Chartered Bank',
        ];
    }

    public function loadCustomers()
    {
        $this->customers = Customer::orderBy('name')->get();
    }

    public function loadCategories()
    {
        $this->categories = ProductCategory::orderBy('name')->get();
    }

    public function loadAllProducts()
    {
        $this->searchResults = ProductDetail::select('product_details.*')
            ->where('product_details.stock_quantity', '>', 0)
            ->where('product_details.status', 'Available')
            ->orderBy('product_details.product_name')
            ->limit(50)
            ->get();
    }

    public function filterByCategory($categoryId = null)
    {
        $this->selectedCategory = $categoryId;

        if ($categoryId) {
            $this->searchResults = ProductDetail::select('product_details.*')
                ->where('product_details.category_id', $categoryId)
                ->where('product_details.stock_quantity', '>', 0)
                ->where('product_details.status', 'Available')
                ->orderBy('product_details.product_name')
                ->limit(50)
                ->get();
        } else {
            $this->loadAllProducts();
        }
    }

    public function loadSaleForEdit($saleId)
    {
        $sale = Sale::with(['customer', 'items.product', 'payments'])->find($saleId);

        if (!$sale) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Sale not found']);
            return;
        }

        // Set the sale editing ID
        $this->editingSaleId = $saleId;

        // Load customer details
        $this->customerId = $sale->customer_id;
        $this->customerType = $sale->customer_type ?? 'wholesale';

        // Clear existing cart
        $this->cart = [];
        $this->quantities = [];
        $this->prices = [];
        $this->discounts = [];
        $this->quantityTypes = [];

        // Load items into cart
        foreach ($sale->items as $item) {
            $productId = $item->product_id;
            $this->cart[$productId] = [
                'id' => $item->product->id,
                'name' => $item->product->product_name,
                'code' => $item->product->product_code,
                'brand' => $item->product->brand->name ?? 'N/A',
                'image' => $item->product->image,
                'price' => $item->price,
                'mrp_price' => $item->product->mrp_price,
                'stock_quantity' => $item->product->stock_quantity + $item->quantity, // Add back the sold quantity
            ];
            $this->quantities[$productId] = $item->quantity;
            $this->prices[$productId] = $item->price;
            $this->discounts[$productId] = $item->discount ?? 0;
            $this->quantityTypes[$productId] = '';
        }

        // Load payment and discount information
        $this->paymentType = $sale->payment_type;
        $this->saleNotes = $sale->notes ?? '';

        // Set discount values if applicable
        $totalDiscount = $sale->discount_amount ?? 0;
        $itemDiscount = 0;
        foreach ($sale->items as $item) {
            $itemDiscount += ($item->discount ?? 0) * $item->quantity;
        }
        $this->calculatedDiscount = $totalDiscount - $itemDiscount;

        // If there was an overall discount, try to determine if it was percentage or amount
        if ($this->calculatedDiscount > 0) {
            $baseAmount = $sale->subtotal - $itemDiscount;
            if ($baseAmount > 0) {
                $percentage = ($this->calculatedDiscount / $baseAmount) * 100;
                if ($percentage <= 100) {
                    $this->discountType = 'percentage';
                    $this->discountValue = round($percentage, 2);
                } else {
                    $this->discountType = 'amount';
                    $this->discountValue = $this->calculatedDiscount;
                }
            }
        }

        $this->updateTotals();

        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Sale loaded for editing']);
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $search = $this->search;

            // Optimized search query with proper indexing and limiting results
            $query = ProductDetail::select('product_details.*')
                ->where(function ($q) use ($search) {
                    $q->where('product_details.product_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('product_details.product_code', 'LIKE', '%' . $search . '%');
                })
                ->where('product_details.stock_quantity', '>', 0)
                ->where('product_details.status', 'Available');

            // Apply category filter if selected
            if ($this->selectedCategory) {
                $query->where('product_details.category_id', $this->selectedCategory);
            }

            $this->searchResults = $query->orderBy('product_details.product_name')
                ->limit(50)
                ->get();
        } else {
            // If search is cleared, reload based on category filter
            if ($this->selectedCategory) {
                $this->filterByCategory($this->selectedCategory);
            } else {
                $this->loadAllProducts();
            }
        }
    }

    public function addToCart($productId)
    {
        $product = ProductDetail::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => 'This product is out of stock.']);
            return;
        }

        if (isset($this->cart[$productId])) {
            if (($this->quantities[$productId] + 1) > $product->stock_quantity) {
                $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Maximum available stock is {$product->stock_quantity}"]);
                return;
            }
            $this->quantities[$productId]++;
        } else {
            $newItem = [
                $productId => [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'code' => $product->product_code,
                    'brand' => $product->brand->name ?? 'N/A',
                    'image' => $product->image ?? null,
                    'image_url' => $product->image_url ?? null,
                    'price' => $product->selling_price,
                    'mrp_price' => $product->mrp_price,
                    'stock_quantity' => $product->stock_quantity,
                ]
            ];

            $this->cart = $newItem + $this->cart;
            $this->prices[$productId] = $product->selling_price ?? 0;
            $this->quantities[$productId] = 1;
            $this->discounts[$productId] = 0;
            $this->quantityTypes[$productId] = '';
        }

        // Reload search results based on current search or category
        if ($this->search) {
            $this->updatedSearch();
        } elseif ($this->selectedCategory) {
            $this->filterByCategory($this->selectedCategory);
        } else {
            $this->loadAllProducts();
        }

        $this->updateTotals();
    }

    public function updatedQuantities($value, $key)
    {
        $this->validateQuantity((int)$key);
    }

    public function updatedPrices($value, $key)
    {
        $value = max(0, floatval($value));
        $this->prices[$key] = $value;

        if (isset($this->discounts[$key]) && $this->discounts[$key] > $value) {
            $this->discounts[$key] = $value;
        }

        $this->updateTotals();
    }

    public function updatedDiscounts($value, $key)
    {
        $price = $this->prices[$key] ?? $this->cart[$key]['price'] ?? 0;
        $this->discounts[$key] = max(0, min(floatval($value), $price));
        $this->updateTotals();
    }

    public function updatedDiscountType()
    {
        $this->discountValue = 0;
        $this->updateTotals();
    }

    public function updatedDiscountValue()
    {
        $this->updateTotals();
    }

    public function validateQuantity($productId)
    {
        if (!isset($this->cart[$productId]) || !isset($this->quantities[$productId])) {
            return;
        }

        $maxAvailable = $this->cart[$productId]['stock_quantity'];
        $currentQuantity = filter_var($this->quantities[$productId], FILTER_VALIDATE_INT);

        if ($currentQuantity === false || $currentQuantity < 1) {
            $this->quantities[$productId] = 1;
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => 'Minimum quantity is 1']);
        } elseif ($currentQuantity > $maxAvailable) {
            $this->quantities[$productId] = $maxAvailable;
            $this->dispatch('show-toast', ['type' => 'warning', 'message' => "Maximum stock is {$maxAvailable}"]);
        }
        $this->updateTotals();
    }

    public function updateQuantity($productId, $quantity)
    {
        if (!isset($this->cart[$productId])) {
            return;
        }

        $maxAvailable = $this->cart[$productId]['stock_quantity'];

        if ($quantity <= 0) {
            $quantity = 1;
        } elseif ($quantity > $maxAvailable) {
            $quantity = $maxAvailable;
            $this->dispatch('show-toast', [
                'type' => 'warning',
                'message' => "Maximum available quantity is {$maxAvailable}"
            ]);
        }

        $this->quantities[$productId] = $quantity;
        $this->updateTotals();
    }

    public function updatePrice($productId, $price)
    {
        if (!isset($this->cart[$productId])) return;

        $price = floatval($price);
        if ($price < 0) $price = 0;

        $this->cart[$productId]['price'] = $price;
        $this->prices[$productId] = $price;
        $this->updateTotals();
    }

    public function updateDiscount($productId, $discount)
    {
        if (!isset($this->cart[$productId])) return;

        $this->discounts[$productId] = max(0, min($discount, $this->cart[$productId]['price'] ?? 0));
        $this->updateTotals();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        unset($this->quantities[$productId]);
        unset($this->discounts[$productId]);
        unset($this->prices[$productId]);
        unset($this->quantityTypes[$productId]);
        $this->updateTotals();
    }

    public function incrementQuantity($productId)
    {
        if (isset($this->quantities[$productId])) {
            $this->quantities[$productId]++;
            $this->updateTotals();
        }
    }

    public function decrementQuantity($productId)
    {
        if (isset($this->quantities[$productId]) && $this->quantities[$productId] > 1) {
            $this->quantities[$productId]--;
            $this->updateTotals();
        }
    }

    public function selectPaymentMethod($method)
    {
        $this->paymentMethod = $method;

        if ($method === 'cash') {
            $this->cashAmount = $this->grandTotal;
            $this->paymentType = 'full';
        } elseif ($method === 'card') {
            $this->paymentType = 'full';
        } elseif ($method === 'cheque') {
            $this->paymentType = 'partial';
        }
    }

    public function showDetail($productId)
    {
        $this->productDetails = ProductDetail::select(
            'id',
            'product_name',
            'product_code',
            'selling_price',
            'stock_quantity',
            'damage_quantity',
            'sold',
            DB::raw("(stock_quantity) as available_stock")
        )
            ->where('id', $productId)
            ->first();

        $this->js('$("#viewDetailModal").modal("show")');
    }

    public function calculateOverallDiscount()
    {
        $baseAmount = $this->subtotal - $this->totalDiscount;

        if ($baseAmount <= 0) {
            $this->calculatedDiscount = 0;
            return;
        }

        if ($this->discountType === 'percentage') {
            $percentage = min(100, max(0, floatval($this->discountValue)));
            $this->calculatedDiscount = ($baseAmount * $percentage) / 100;
        } else {
            // Amount-based discount
            $this->calculatedDiscount = min($baseAmount, max(0, floatval($this->discountValue)));
        }
    }

    public function updateTotals()
    {
        $grossSubtotal = 0;
        $this->totalDiscount = 0;

        foreach ($this->cart as $id => $item) {
            $price = $this->prices[$id] ?? $item['price'] ?? 0;
            $qty = $this->quantities[$id] ?? 1;
            $discount = $this->discounts[$id] ?? 0;
            $grossSubtotal += $price * $qty;
            $this->totalDiscount += $discount * $qty;
        }

        // Subtotal after unit discounts
        $this->subtotal = $grossSubtotal - $this->totalDiscount;

        // Calculate overall discount
        $this->calculateOverallDiscount();

        // Grand total = subtotal after unit discounts - overall discount
        $this->grandTotal = $this->subtotal - $this->calculatedDiscount;

        // Ensure grand total doesn't go negative
        if ($this->grandTotal < 0) {
            $this->grandTotal = 0;
        }
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->quantities = [];
        $this->discounts = [];
        $this->prices = [];
        $this->quantityTypes = [];
        $this->discountType = 'percentage';
        $this->discountValue = 0;
        $this->calculatedDiscount = 0;
        $this->updateTotals();
    }

    #[On('debugReceipt')]
    public function debugReceipt()
    {
        Log::info('Debug receipt called', [
            'receipt' => $this->receipt,
            'cart' => $this->cart,
            'customerId' => $this->customerId
        ]);

        $this->dispatch('show-toast', ['type' => 'info', 'message' => 'Receipt debug logged. Check logs for details.']);
    }

    public function saveCustomer()
    {
        $this->validate([
            'newCustomerName' => 'required',
        ]);

        $customer = Customer::create([
            'name' => $this->newCustomerName,
            'phone' => $this->newCustomerPhone,
            'email' => $this->newCustomerEmail,
            'type' => $this->newCustomerType,
            'address' => $this->newCustomerAddress,
            'notes' => $this->newCustomerNotes,
        ]);

        $this->loadCustomers();
        $this->customerId = $customer->id;

        $this->newCustomerName = '';
        $this->newCustomerPhone = '';
        $this->newCustomerEmail = '';
        $this->newCustomerAddress = '';
        $this->newCustomerNotes = '';

        $this->js('$("#addCustomerModal").modal("hide")');
        $this->js('swal.fire("Success", "Customer added successfully!", "success")');
    }

    public function calculateBalanceAmount()
    {
        if ($this->paymentType == 'partial') {
            $this->balanceAmount = $this->grandTotal - $this->initialPaymentAmount;
        } else {
            $this->balanceAmount = 0;
        }
    }

    public function updatedPaymentType($value)
    {
        if ($value == 'partial') {
            $this->calculateBalanceAmount();
        } else {
            $this->balanceAmount = 0;
        }
    }

    public function addCheque()
    {
        $this->validate([
            'newCheque.number' => 'required|string|max:255',
            'newCheque.bank' => 'required|string|max:255',
            'newCheque.date' => 'required|date|after_or_equal:today',
            'newCheque.amount' => 'required|numeric|min:0.01',
        ], [
            'newCheque.number.required' => 'Cheque number is required.',
            'newCheque.bank.required' => 'Bank name is required.',
            'newCheque.date.required' => 'Cheque date is required.',
            'newCheque.date.after_or_equal' => 'Cheque date cannot be in the past.',
            'newCheque.amount.required' => 'Cheque amount is required.',
            'newCheque.amount.min' => 'Cheque amount must be greater than 0.',
        ]);

        $chequeDate = strtotime($this->newCheque['date']);
        $today = strtotime(date('Y-m-d'));

        if ($chequeDate < $today) {
            $this->js('swal.fire("Error", "Cheque date cannot be in the past. Please select today\'s date or a future date.", "error")');
            return;
        }

        $this->cheques[] = [
            'number' => $this->newCheque['number'],
            'bank' => $this->newCheque['bank'],
            'date' => $this->newCheque['date'],
            'amount' => floatval($this->newCheque['amount']),
        ];

        $this->resetChequeForm();
    }

    public function resetChequeForm()
    {
        $this->newCheque = [
            'number' => '',
            'bank' => '',
            'date' => '',
            'amount' => '',
        ];
    }

    public function removeCheque($index)
    {
        if (isset($this->cheques[$index])) {
            unset($this->cheques[$index]);
            $this->cheques = array_values($this->cheques);
        }
    }

    #[On('applyDiscount')]
    public function applyDiscount($productId, $type, $value)
    {
        if ($productId === 'overall') {
            // Apply overall discount
            $this->discountType = $type;
            $this->discountValue = $value;
        } else {
            // Apply product-specific discount
            if ($type === 'percentage') {
                // Calculate percentage discount on product price
                $price = $this->prices[$productId] ?? 0;
                $discountAmount = ($price * $value) / 100;
            } else {
                // Fixed amount discount
                $discountAmount = $value;
            }

            $this->discounts[$productId] = $discountAmount;
        }

        $this->updateTotals();
    }

    #[On('completeSaleWithPayment')]
    public function handleCompleteSaleWithPayment($data = null)
    {
        if (empty($this->cart)) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Please add items to the cart.']);
            return;
        }

        $this->validate([
            'customerId' => 'nullable', // Allow walk-in customers
        ]);

        // Validate and extract data parameter
        if (!$data) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Payment data is missing.']);
            return;
        }

        Log::info('Payment data received:', ['data' => $data]);
        Log::info('Current cart:', ['cart' => $this->cart, 'quantities' => $this->quantities]);

        // Handle data parameter - could be array or direct object
        $paymentDetails = is_array($data) && isset($data[0]) ? $data[0] : $data;

        if (!is_array($paymentDetails) || !isset($paymentDetails['method'])) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Invalid payment data format.']);
            return;
        }

        $method = $paymentDetails['method'];
        $amount = floatval($paymentDetails['amount'] ?? 0);
        $balance = floatval($paymentDetails['balance'] ?? 0);

        // Set payment details based on method
        if ($method === 'cash') {
            $this->cashAmount = $amount;
            $this->cheques = [];
            $this->paymentType = ($amount >= $this->grandTotal) ? 'full' : 'partial';
        } elseif ($method === 'card') {
            $this->cashAmount = $amount;  // For simplicity, treating card as cash
            $this->cheques = [];
            $this->paymentType = ($amount >= $this->grandTotal) ? 'full' : 'partial';
        } elseif ($method === 'cheque') {
            // Handle multiple cheques format
            if (isset($paymentDetails['cheques']) && is_array($paymentDetails['cheques'])) {
                $this->cheques = $paymentDetails['cheques'];
                $totalChequeAmount = collect($this->cheques)->sum('amount');
            } else {
                // Fallback for single cheque format
                $this->cheques = [[
                    'number' => $paymentDetails['chequeNumber'] ?? '',
                    'bank' => $paymentDetails['chequeBank'] ?? '',
                    'date' => $paymentDetails['chequeDate'] ?? date('Y-m-d'),
                    'amount' => $amount,
                ]];
                $totalChequeAmount = $amount;
            }
            $this->cashAmount = 0;
            $this->paymentType = ($totalChequeAmount >= $this->grandTotal) ? 'full' : 'partial';
        } elseif ($method === 'credit') {
            $this->cashAmount = 0;
            $this->cheques = [];
            $this->paymentType = 'credit';
            $this->balanceDueDate = $paymentDetails['dueDate'] ?? null;
            $this->saleNotes = $paymentDetails['notes'] ?? '';
        } elseif ($method === 'multiple') {
            // Handle multiple payments
            $this->cashAmount = 0;
            $this->cheques = [];

            foreach ($paymentDetails['payments'] as $payment) {
                if ($payment['method'] === 'cash') {
                    $this->cashAmount += $payment['amount'];
                } elseif ($payment['method'] === 'cheque') {
                    $this->cheques[] = [
                        'number' => $payment['chequeNumber'],
                        'bank' => $payment['chequeBank'],
                        'date' => $payment['chequeDate'],
                        'amount' => $payment['amount'],
                    ];
                }
                // Add card handling if needed
            }

            $totalPaid = $this->cashAmount + collect($this->cheques)->sum('amount');
            $this->paymentType = ($totalPaid >= $this->grandTotal) ? 'full' : 'partial';
        }

        // Complete the sale
        $this->completeSale();
    }

    public function completeSale()
    {
        if (empty($this->cart)) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Please add items to the cart.']);
            return;
        }

        $this->validate([
            'customerId' => 'nullable', // Allow walk-in customers  
            'paymentType' => 'required|in:full,partial,credit',
        ]);

        $totalChequeAmount = collect($this->cheques)->sum('amount');
        $totalPaid = floatval($this->cashAmount) + floatval($totalChequeAmount);

        // Don't override amounts for full payment - use the actual paid amounts
        if ($this->paymentType === 'full') {
            if (abs($totalPaid - $this->grandTotal) > 0.01) {
                $errorMsg = 'Full payment must equal the grand total. Paid: Rs. ' . number_format($totalPaid, 2) . ', Required: Rs. ' . number_format($this->grandTotal, 2);
                $this->dispatch('show-toast', ['type' => 'error', 'message' => $errorMsg]);
                return;
            }
        }

        if ($this->paymentType === 'partial') {
            if (floatval($this->cashAmount) < 0) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Cash amount cannot be negative.']);
                return;
            }

            if (floatval($this->cashAmount) > 0 && floatval($this->cashAmount) > $this->grandTotal) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Cash amount cannot exceed the grand total.']);
                return;
            }

            if (!empty($this->cheques)) {
                foreach ($this->cheques as $cheque) {
                    if (floatval($cheque['amount']) <= 0) {
                        $this->dispatch('show-toast', ['type' => 'error', 'message' => 'All cheque amounts must be greater than 0.']);
                        return;
                    }
                }
            }
            if (($totalPaid <= 0) || ($totalPaid > $this->grandTotal)) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'For partial payments, the total paid amount must be greater than 0 and not exceed the grand total.']);
                return;
            }
        }

        try {
            DB::beginTransaction();

            $totalChequeAmount = collect($this->cheques)->sum('amount');
            $totalPaid = floatval($this->cashAmount) + floatval($totalChequeAmount);

            if ($this->paymentType === 'full' || abs($totalPaid - $this->grandTotal) < 0.01) {
                $paymentStatus = 'paid';
                $dueAmount = 0;
            } elseif ($this->paymentType === 'credit') {
                $paymentStatus = 'credit';
                $dueAmount = $this->grandTotal;
            } else {
                $paymentStatus = 'pending';
                $dueAmount = $this->grandTotal - $totalPaid;
            }

            // Calculate total discount (item discounts + overall discount)
            $totalDiscountAmount = $this->totalDiscount + $this->calculatedDiscount;

            // Check if editing or creating new sale
            if ($this->editingSaleId) {
                // Editing existing sale
                $sale = Sale::find($this->editingSaleId);

                // Restore the original quantities to stock
                foreach ($sale->items as $item) {
                    $product = ProductDetail::find($item->product_id);
                    $product->stock_quantity += $item->quantity;
                    $product->sold -= $item->quantity;
                    $product->save();
                }

                // Delete existing items
                SalesItem::where('sale_id', $sale->id)->delete();

                // Update sale record
                $customerType = 'retail';  // Default for walk-in customers
                if ($this->customerId) {
                    $customer = Customer::find($this->customerId);
                    if ($customer) {
                        $customerType = $customer->type;
                    }
                }

                $sale->update([
                    'customer_id'      => $this->customerId,
                    'customer_type'    => $customerType,
                    'subtotal'         => $this->subtotal,
                    'discount_amount'  => $totalDiscountAmount,
                    'total_amount'     => $this->grandTotal,
                    'payment_type'     => $this->paymentType,
                    'payment_status'   => $paymentStatus,
                    'notes'            => $this->saleNotes ?: null,
                    'due_amount'       => $dueAmount,
                ]);
            } else {
                // Creating new sale
                $customerType = 'retail';  // Default for walk-in customers
                if ($this->customerId) {
                    $customer = Customer::find($this->customerId);
                    if ($customer) {
                        $customerType = $customer->type;
                    }
                }

                $sale = Sale::create([
                    'invoice_number'   => Sale::generateInvoiceNumber(),
                    'customer_id'      => $this->customerId,
                    'user_id'          => auth()->id(),
                    'customer_type'    => $customerType,
                    'subtotal'         => $this->subtotal,
                    'discount_amount'  => $totalDiscountAmount,
                    'total_amount'     => $this->grandTotal,
                    'payment_type'     => $this->paymentType,
                    'payment_status'   => $paymentStatus,
                    'notes'            => $this->saleNotes ?: null,
                    'due_amount'       => $dueAmount,
                    'payment_status'   => $paymentStatus,
                    'notes'            => $this->saleNotes ?: null,
                    'due_amount'       => $dueAmount,
                ]);
            }

            foreach ($this->cart as $id => $item) {
                $quantityToSell = $this->quantities[$id];
                $price = $this->prices[$id] ?? $item['price'];
                $itemDiscount = $this->discounts[$id] ?? 0;
                $total = ($price * $quantityToSell) - ($itemDiscount * $quantityToSell);

                SalesItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $quantityToSell,
                    'price' => $price,
                    'discount' => $itemDiscount,
                    'total' => $total,
                ]);

                $productStock = ProductDetail::find($item['id']);
                $productStock->stock_quantity -= $quantityToSell;
                $productStock->sold += $quantityToSell;
                $productStock->save();
            }

            // Create cash payment record if there's a cash amount
            if (floatval($this->cashAmount) > 0) {
                Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => floatval($this->cashAmount),
                    'payment_method' => 'cash',
                    'is_completed' => true,
                    'status' => 'Paid',
                    'payment_date' => now(),
                ]);
            }

            foreach ($this->cheques as $cheque) {
                $payment = Payment::create([
                    'sale_id' => $sale->id,
                    'amount' => floatval($cheque['amount']),
                    'payment_method' => 'cheque',
                    'payment_reference' => $cheque['number'],
                    'bank_name' => $cheque['bank'],
                    'is_completed' => false,
                    'status' => 'Pending',
                    'payment_date' => $cheque['date'],
                ]);

                Cheque::create([
                    'cheque_number' => $cheque['number'],
                    'cheque_date' => $cheque['date'],
                    'bank_name' => $cheque['bank'],
                    'cheque_amount' => $cheque['amount'],
                    'status' => 'pending',
                    'customer_id' => $this->customerId,  // Can be null for walk-in customers
                    'payment_id' => $payment->id,
                ]);

                Log::info('Cheque created', ['cheque_number' => $cheque['number'], 'customer_id' => $this->customerId]);
            }

            DB::commit();

            // Log successful save
            Log::info('Sale completed successfully', ['sale_id' => $sale->id, 'invoice' => $sale->invoice_number]);

            // Generate formatted receipt data after successful save
            $saleWithRelations = Sale::with(['customer', 'items.product', 'payments'])->find($sale->id);
            $customer = $saleWithRelations->customer;

            $items = [];
            $subtotalBeforeDiscounts = 0;
            foreach ($this->cart as $id => $item) {
                $quantity = $this->quantities[$id];
                $price = $this->prices[$id] ?? $item['price'];
                $itemDiscount = $this->discounts[$id] ?? 0;
                $total = ($price * $quantity) - ($itemDiscount * $quantity);
                $subtotalBeforeDiscounts += $price * $quantity;

                $items[] = [
                    'name' => $item['name'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'mrp_price' => $item['mrp_price'] ?? null,
                    'discount' => $itemDiscount,
                    'total' => $total,
                ];
            }

            $this->receipt = [
                'invoice_number' => $saleWithRelations->invoice_number,
                'date' => $saleWithRelations->created_at->format('d/m/Y'),
                'customer_name' => $customer->name ?? 'Walk-in Customer',
                'customer_address' => $customer->address ?? 'N/A',
                'customer_phone' => $customer->phone ?? 'N/A',
                'items' => $items,
                'subtotal' => $subtotalBeforeDiscounts,
                'discount' => $totalDiscountAmount,
                'total' => $this->grandTotal,
                'payment_type' => ucfirst($this->paymentType),
                'payments' => $saleWithRelations->payments->map(function ($payment) {
                    return [
                        'method' => ucfirst($payment->payment_method),
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                    ];
                })->toArray(),
            ];

            Log::info('Receipt generated', ['receipt' => $this->receipt]);

            $message = $this->editingSaleId ? "Sale updated successfully!" : "Sale completed successfully!";
            $this->dispatch('show-toast', ['type' => 'success', 'message' => $message]);

            // Show receipt modal after successful completion
            $this->dispatch('showModal', ['modalId' => 'receiptModal']);

            $this->clearCart();
            $this->editingSaleId = null;
            $this->resetPaymentInfo();

            Log::info('Sale completion finished', ['receipt_set' => !empty($this->receipt)]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Sale completion error: ' . $e->getMessage());
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Error completing sale: ' . $e->getMessage()]);
        }
    }

    public function resetPaymentInfo()
    {
        $this->paymentType = 'partial';
        $this->paymentMethod = '';
        $this->paymentReceiptImage = null;
        $this->paymentReceiptImagePreview = null;
        $this->bankName = '';
        $this->customerId = null;

        $this->cashAmount = 0;
        $this->cheques = [];
        $this->newCheque = [
            'number' => '',
            'bank' => '',
            'date' => '',
            'amount' => '',
        ];

        $this->discountType = 'percentage';
        $this->discountValue = 0;
        $this->calculatedDiscount = 0;

        $this->initialPaymentAmount = 0;
        $this->initialPaymentMethod = '';
        $this->initialPaymentReceiptImage = null;
        $this->initialPaymentReceiptImagePreview = null;
        $this->initialBankName = '';

        $this->balanceAmount = 0;
        $this->balancePaymentMethod = '';
        $this->balanceDueDate = date('Y-m-d', strtotime('+7 days'));
        $this->balancePaymentReceiptImage = null;
        $this->balancePaymentReceiptImagePreview = null;
        $this->balanceBankName = '';
    }

    public function render()
    {
        return view('livewire.admin.store-billing-grid');
    }
}
