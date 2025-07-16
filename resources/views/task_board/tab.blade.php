 @php
     use App\Models\Project_offer;
     use App\Models\Project_sales_order;
     use App\Models\Project_survey;

     $total_survey_pending = Project_survey::whereNotIn('projsur_status', ['Cancelled', 'Done'])->count();
     $total_offer_pending = Project_offer::whereNotIn('projoff_status', ['Cancelled', 'Done'])->count();
     $total_so_pending = Project_sales_order::whereNotIn('projso_status', ['Cancelled', 'Done'])->count();
 @endphp


 <ul class="nav nav-pills mb-2">
     @if (Auth::user()->hasPermissionTo('task_board.pre_sales'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'pre-sales' ? 'active' : '' }}"
                 {{ $assignee == 'pre-sales' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'pre-sales']) }}">Pre
                 Sales
                 @if ($total_survey_pending > 0)
                     <span class="badge text-bg-warning rounded-pill">{{ $total_survey_pending }}</span>
                 @endif
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.sales_admin'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'sales-admin' ? 'active' : '' }}"
                 {{ $assignee == 'sales-admin' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'sales-admin']) }}">Sales
                 Admin
                 @if ($total_offer_pending > 0)
                     <span class="badge text-bg-warning rounded-pill">{{ $total_offer_pending }}</span>
                 @endif
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.finance_accounting'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'finance_accounting' ? 'active' : '' }}"
                 {{ $assignee == 'finance_accounting' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'finance_accounting']) }}">Finance & Accounting
                 @if ($project_offer->where('projoff_status', '!=', 'Done')->count() > 0)
                     <span
                         class="badge text-bg-warning rounded-pill">{{ $project_offer->where('projoff_status', '!=', 'Done')->count() }}</span>
                 @endif
             </a>
         </li>
     @endif
     @if (Auth::user()->hasPermissionTo('task_board.operation'))
         <li class="nav-item">
             <a class="nav-link {{ $assignee == 'operation' ? 'active' : '' }}"
                 {{ $assignee == 'operation' ? 'aria-current="page"' : '' }}
                 href="{{ route('task_board.index', ['assignee' => 'operation']) }}">Operation
                 @if ($project_offer->where('projoff_status', '!=', 'Done')->count() > 0)
                     <span
                         class="badge text-bg-warning rounded-pill">{{ $project_offer->where('projoff_status', '!=', 'Done')->count() }}</span>
                 @endif
             </a>
         </li>
     @endif
 </ul>

 <hr class="col-12 ">

 @if ($assignee == 'sales-admin')
     <div class="mb-2">
         <span class="badge text-bg-warning rounded-pill">Quotation : {{ $total_offer_pending }}</span>
         <span class="badge text-bg-primary rounded-pill">Sale Order : {{ $total_so_pending }}</span>
         <span class="badge text-bg-success rounded-pill">Work Order : 5</span>
     </div>
 @endif

 <div class="row g-5">
     <div class="d-flex flex-row gap-2">
         @if ($assignee == 'sales-admin')
             <div class="flex-fill w-100">
                 <label for="doc_type" class="form-label">Document</label>
                 <select class="form-select flex-fill" id="doc_type" name="doc_type">
                     <option value="quotation" {{ request()->get('doc_type') == 'quotation' ? 'selected' : '' }}>
                         Quotation</option>
                     <option value="sales-order" {{ request()->get('doc_type') == 'sales-order' ? 'selected' : '' }}>
                         Sales Order</option>
                     <option value="work-order" {{ request()->get('doc_type') == 'work-order' ? 'selected' : '' }}>
                         Work Order</option>
                 </select>
             </div>
         @endif
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
         <div class="flex-fill" style="width: 22%">
             <label for="show" class="form-label">Show</label>
             <select class="form-select" id="show" name="show">
                 {{-- <option value="5" {{ request()->get('show') == '5' ? 'selected' : '' }}>
                                        5
                                    </option> --}}
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
     </div>
 </div>
