 @php
     use App\Models\Project_offer;
     use App\Models\Project_sales_order;
     use App\Models\Project_survey;
     use App\Models\Project_invoice_dp;
     use App\Models\Project_work_order;
     use App\Models\Work_order;

     $total_survey_pending = Project_survey::whereNotIn('projsur_status', ['Cancelled', 'Done'])->count();
     $total_offer_pending = Project_offer::whereNotIn('projoff_status', ['Cancelled', 'Done'])->count();
     $total_so_pending = Project_sales_order::whereNotIn('projso_status', ['Cancelled', 'Done'])->count();
     $total_invdp_pending = Project_invoice_dp::whereNotIn('projinvdp_status', ['Cancelled', 'Done'])->count();
     $total_inv_pending = 0;
     //  $total_wo_pending = Project_work_order::whereNotIn('projwo_status', ['Cancelled', 'Done'])->count();
     $total_wo_pending = Work_order::whereNotIn('wo_status', ['Cancelled', 'Done'])->count();
 @endphp


 <ul class="nav nav-pills mb-2">
     @if (Auth::user()->hasPermissionTo('task_board.pre_sales'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'pre-sales' ? 'active' : '' }}"
                 {{ $assignee == 'pre-sales' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'pre-sales']) }}">Pre
                 Sales
                 {{-- @if ($total_survey_pending > 0)
                     <span class="badge text-bg-warning rounded-pill">
                         {{ $total_survey_pending }}
                     </span>
                 @endif --}}
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.sales_admin'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'sales-admin' ? 'active' : '' }}"
                 {{ $assignee == 'sales-admin' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'sales-admin']) }}">Sales
                 Admin
                 {{-- @if ($total_offer_pending + $total_so_pending > 0)
                     <span class="badge text-bg-warning rounded-pill">
                         {{ $total_offer_pending + $total_so_pending }}
                     </span>
                 @endif --}}
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.finance_accounting'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'finance-accounting' ? 'active' : '' }}"
                 {{ $assignee == 'finance-accounting' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'finance-accounting']) }}">Finance Accounting
                 {{-- @if ($total_invdp_pending > 0)
                     <span class="badge text-bg-warning rounded-pill">
                         {{ $total_invdp_pending }}
                     </span>
                 @endif --}}
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.operation'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'operation' ? 'active' : '' }}"
                 {{ $assignee == 'operation' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'operation']) }}">Operation
                 {{-- @if ($project_work_order->where('projwo_status', '!=', 'Done')->count() > 0)
                     <span
                         class="badge text-bg-warning rounded-pill">{{ $project_work_order->where('projwo_status', '!=', 'Done')->count() }}</span>
                 @endif --}}
             </a>
         </li>
     @endif
 </ul>

 <hr class="col-12 ">

 {{-- @if ($assignee == 'sales-admin')
     <div class="mb-4 h5">
         <a href="#" class="d-inline-block text-decoration-none"
             onclick="$('#doc_type').val('quotation').trigger('change').on('change', function(){search()}); return false;">
             <span class="badge text-bg-{{ $doc_type == 'quotation' ? 'success' : 'secondary' }}">Quotation
                 :
                 @if ($total_offer_pending > 0)
                     <span class="badge text-bg-warning rounded-pill" style="font-size: 16px">
                         {{ $total_offer_pending }}
                     </span>
                 @else
                     <span class="badge text-bg-{{ $doc_type == 'quotation' ? 'success' : 'secondary' }} rounded-pill"
                         style="font-size: 16px">
                         {{ $total_offer_pending }}
                     </span>
                 @endif
             </span>
         </a>
         <a href="#" class="d-inline-block text-decoration-none"
             onclick="$('#doc_type').val('sales-order').trigger('change').on('change', function(){search()}); return false;">
             <span class="badge text-bg-{{ $doc_type == 'sales-order' ? 'success' : 'secondary' }}">Sale
                 Order
                 :
                 @if ($total_so_pending > 0)
                     <span class="badge text-bg-warning rounded-pill" style="font-size: 16px">
                         {{ $total_so_pending }}
                     </span>
                 @else
                     <span
                         class="badge text-bg-{{ $doc_type == 'sales-order' ? 'success' : 'secondary' }} rounded-pill"
                         style="font-size: 16px">
                         {{ $total_so_pending }}
                     </span>
                 @endif
             </span>
         </a>
     </div>
 @elseif($assignee == 'finance-accounting')
     <div class="mb-4 h5">
         <a href="" class="d-inline-block text-decoration-none"
             onclick="$('#doc_type').val('invoice-dp').trigger('change').on('change', function(){search()}); return false;">
             <span class="badge text-bg-{{ $doc_type == 'invoice-dp' ? 'success' : 'secondary' }} ">Invoice
                 DP
                 :
                 @if ($total_invdp_pending > 0)
                     <span class="badge text-bg-warning rounded-pill" style="font-size: 16px">
                         {{ $total_invdp_pending }}
                     </span>
                 @else
                     <span class="badge text-bg-{{ $doc_type == 'invoice-dp' ? 'success' : 'secondary' }} rounded-pill"
                         style="font-size: 16px">
                         {{ $total_invdp_pending }}
                     </span>
                 @endif
             </span>
         </a>

         <a href="" class="d-inline-block text-decoration-none"
             onclick="$('#doc_type').val('invoice').trigger('change').on('change', function(){search()}); return false;">
             <span class="badge text-bg-{{ $doc_type == 'invoice' ? 'success' : 'secondary' }} ">Invoce :
                 @if ($total_inv_pending > 0)
                     <span class="badge text-bg-warning rounded-pill" style="font-size: 16px">
                         {{ $total_inv_pending }}
                     </span>
                 @else
                     <span class="badge text-bg-{{ $doc_type == 'invoice' ? 'success' : 'secondary' }} rounded-pill"
                         style="font-size: 16px">
                         {{ $total_inv_pending }}
                     </span>
                 @endif
             </span>
         </a>
     </div>
 @elseif($assignee == 'operation')
     <div class="mb-4 h5">
         <a href="" class="d-inline-block text-decoration-none"
             onclick="$('#doc_type').val('work-order').trigger('change').on('change', function(){search()}); return false;">
             <span class="badge text-bg-{{ $doc_type == 'work-order' ? 'success' : 'secondary' }} ">Work
                 Order :
                 @if ($total_wo_pending > 0)
                     <span class="badge text-bg-warning rounded-pill" style="font-size: 16px">
                         {{ $total_wo_pending }}
                     </span>
                 @else
                     <span class="badge text-bg-{{ $doc_type == 'work-order' ? 'success' : 'secondary' }} rounded-pill"
                         style="font-size: 16px">
                         {{ $total_wo_pending }}
                     </span>
                 @endif
             </span>
         </a>
     </div>
 @endif --}}

 <div class="row g-5">
     <div class="d-flex flex-row gap-2">
         @if ($assignee == 'sales-admin')
             <div class="flex-fill w-100">
                 <label for="doc_type" class="form-label">Document</label>
                 <select class="form-select flex-fill" id="doc_type" name="doc_type">
                     <option value="quotation" {{ $doc_type == 'quotation' ? 'selected' : '' }}>
                         Quotation</option>
                     <option value="sales-order" {{ $doc_type == 'sales-order' ? 'selected' : '' }}>
                         Sales Order</option>
                     <option value="work-order" {{ $doc_type == 'work-order' ? 'selected' : '' }}>
                         Create Work Order</option>
                 </select>
             </div>
         @elseif($assignee == 'finance-accounting')
             <div class="flex-fill w-100">
                 <label for="doc_type" class="form-label">Document</label>
                 <select class="form-select flex-fill" id="doc_type" name="doc_type">
                     <option value="invoice-dp" {{ $doc_type == 'invoice-dp' ? 'selected' : '' }}>
                         Invoice DP</option>
                     <option value="invoice" {{ $doc_type == 'invoice' ? 'selected' : '' }}>
                         Invoice</option>
                 </select>
             </div>
         @elseif ($assignee == 'operation')
             <div class="flex-fill w-100">
                 <label for="doc_type" class="form-label">Document</label>
                 <select class="form-select flex-fill" id="doc_type" name="doc_type">
                     <option value="work-order" {{ $doc_type == 'work-order' ? 'selected' : '' }}>
                         Work Order</option>
                 </select>
             </div>
         @endif

         @if ($assignee != 'sales-admin' || $doc_type != 'work-order')
             <div class="flex-fill w-100">
                 <label for="status" class="form-label">Status</label>
                 <select class="form-select flex-fill" id="status" name="status">
                     <option value="All" {{ request()->get('status') == 'All' ? 'selected' : '' }}>
                         All</option>
                     <option value="Open" {{ request()->get('status') == 'Open' ? 'selected' : '' }}>
                         Open</option>
                     <option value="Started" {{ request()->get('status') == 'Started' ? 'selected' : '' }}>
                         Started</option>
                     <option value="Hold" {{ request()->get('status') == 'Hold' ? 'selected' : '' }}>
                         Hold</option>
                     <option value="Done" {{ request()->get('status') == 'Done' ? 'selected' : '' }}>
                         Done</option>
                 </select>
             </div>

             <div class="flex-fill w-100">
                 <label for="search" class="form-label">Project ID</label>
                 <input type="text" id="search" name="search" class="form-control" placeholder=""
                     value="{{ request()->get('search') }}">
             </div>

             @if (Auth::user()->hasAnyPermission([
                     'task_board.pre_sales',
                     'task_board.sales_admin',
                     'task_board.operation',
                     'task_board.finance_accounting',
                 ]))
                 <div class="flex-fill" style="width: 25%">
                     <label for="taker" class="form-label">Taker</label>
                     <select class="form-select" id="taker" name="taker">
                         <option value="All" {{ request()->get('taker') == 'All' ? 'selected' : '' }}>
                             All
                         </option>
                         <option value="Me" {{ request()->get('taker') == 'Me' ? 'selected' : '' }}>
                             Me
                         </option>
                     </select>
                 </div>
             @endif

             <div class="flex-fill" style="width: 25%">
                 <label for="show" class="form-label">Show</label>
                 <select class="form-select" id="show" name="show">
                     <option value="10" {{ request()->get('show') == '10' ? 'selected' : '' }}>
                         10
                     </option>
                     <option value="25" {{ request()->get('show') == '25' ? 'selected' : '' }}>
                         25
                     </option>
                     <option value="50" {{ request()->get('show') == '50' ? 'selected' : '' }}>
                         50
                     </option>
                     <option value="100" {{ request()->get('show') == '100' ? 'selected' : '' }}>
                         100
                     </option>
                 </select>
             </div>
         @endif
     </div>
 </div>
