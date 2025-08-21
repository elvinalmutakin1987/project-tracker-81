 <div class="row">
     <div class="col">
         <table id="table1" class="table table-striped mt-3 table-sm">
             <thead class="table-group-divider">
                 <tr>
                     <th>Project ID</th>
                     <th>Project Name</th>
                     <th>Customer</th>
                     <th>Permit By</th>
                     <th>Permit At</th>
                     <th class="text-end">Action</th>
                 </tr>
             </thead>
             <tbody class="table-group-divider">
                 @if ($permit_wo->count() == 0)
                     <tr>
                         <td colspan="100%" class="text-center">No data displayed</td>
                     </tr>
                 @else
                     @foreach ($permit_wo as $d)
                         <tr>
                             <td>
                                 <a
                                     href="{{ route('task_board.show', ['project' => $d->project_id, 'assignee' => $assignee, 'doc_type' => $doc_type]) }}">{{ $d->project->proj_number }}</a>
                             </td>
                             <td>{{ $d->project->proj_name }}</td>
                             <td>{{ $d->project->customer->cust_name }}</td>
                             <td>{{ $d->permitby->name ?? '-' }}</td>
                             <td>{{ $d->projinvdp_permit_at }}</td>
                             <td class="text-end">
                                 <div class="d-inline-flex gap-1">
                                     <a class="btn btn-warning btn-sm"
                                         href="{{ route('task_board.create_work_order', $d->id) }}">Create Work
                                         Order</a>
                                 </div>
                             </td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
         <nav>
             {{ $permit_wo->links('pagination::bootstrap-5') }}
         </nav>
     </div>
 </div>
