 <div class="row">
     <div class="col">
         <table id="table1" class="table table-striped mt-3 table-sm">
             <thead class="table-group-divider">
                 <tr>
                     <th>Task ID</th>
                     <th>Project ID</th>
                     <th>Work Order ID</th>
                     <th>Project Name</th>
                     <th>Customer</th>
                     <th>Taken By</th>
                     <th>Started At</th>
                     <th>Finished At</th>
                     <th>Status</th>
                     <th>Aging</th>
                     <th class="text-end">Action</th>
                 </tr>
             </thead>
             <tbody class="table-group-divider">
                 @if ($work_order->count() == 0)
                     <tr>
                         <td colspan="100%" class="text-center">No data displayed</td>
                     </tr>
                 @else
                     @foreach ($work_order as $d)
                         @php
                             $isMoreThan36Hours = false;
                             $isMoreThan48Hours = false;
                             $now = \Carbon\Carbon::now();
                             $started_at = \Carbon\Carbon::parse($d->projwo_started_at);
                             if ($d->projwo_started_at) {
                                 $started_at = \Carbon\Carbon::parse($d->projwo_started_at);
                                 $diffInHours = $started_at->diffInHours($now);

                                 $isMoreThan36Hours = $diffInHours >= 36 && $diffInHours < 48;
                                 $isMoreThan48Hours = $diffInHours >= 48;
                             }
                         @endphp
                         <tr
                             class="
                        @if ($d->wo_status == 'Done') table-success
                        @elseif(in_array($d->wo_status, ['Started', 'On Going', 'Hold', 'Revisi Mesin']))
                            @if ($isMoreThan36Hours == true) table-warning @endif
                            @if ($isMoreThan48Hours == true) table-danger @endif
                        @endif
                        ">
                             <td>{{ $d->wo_number }}</td>
                             <td>
                                 <a
                                     href="{{ route('task_board.show', ['project' => $d->project_id, 'assignee' => $assignee, 'doc_type' => $doc_type]) }}">{{ $d->project->proj_number }}</a>
                             </td>
                             <td>
                                 <a
                                     href="{{ route('work_order.show', ['work_order' => $d->id, 'project' => $d->project_id, 'assignee' => $assignee, 'doc_type' => $doc_type]) }}">{{ $d->wo_number }}</a>
                             </td>
                             <td>{{ $d->project->proj_name }}</td>
                             <td>{{ $d->project->customer->cust_name }}</td>
                             <td>{{ $d->createdby->name ?? '-' }}</td>
                             <td>{{ $d->wo_started_at ?? '-' }}</td>
                             <td>{{ $d->wo_finished_at ?? '-' }}</td>
                             <td>{{ $d->wo_status }}</td>
                             <td>
                                 @if (in_array($d->wo_status, ['Started', 'Hold']))
                                     @php
                                         $now = \Carbon\Carbon::now();
                                         $started_at = \Carbon\Carbon::parse($d->wo_started_at);
                                         $aging = '-';
                                         if ($d->projso_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($now);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @elseif($d->wo_status == 'Done')
                                     @php
                                         $started_at = \Carbon\Carbon::parse($d->wo_started_at);
                                         $finished_at = \Carbon\Carbon::parse($d->wo_finished_at);
                                         $aging = '-';
                                         if ($d->wo_started_at) {
                                             $diffInSeconds = $started_at->diffInSeconds($finished_at);
                                             $hours = floor($diffInSeconds / 3600);
                                             $minutes = floor(($diffInSeconds % 3600) / 60);
                                             $aging = sprintf('%02d:%02d', $hours, $minutes);
                                         }
                                     @endphp
                                     {{ $aging }}
                                 @else
                                     -
                                 @endif
                             </td>
                             <td class="text-end">
                                 <div class="d-inline-flex gap-1">
                                     @if ($d->wo_status == 'Open')
                                         <form class="d-inline"
                                             action="{{ route('task_board.take_work_order', $d->id) }}" method="POST"
                                             id="form-take{{ $d->id }}">
                                             @csrf
                                             @method('PUT')
                                             <a class="btn btn-warning btn-sm" href="#" role="button"
                                                 onclick="take_task({{ $d->id }}); return false;">Pick
                                                 Up</a>
                                         </form>
                                     @else
                                         @if ($d->wo_status != 'Done')
                                             @include('task_board.button_wo')
                                         @else
                                             {{-- @if ($d->projso_status == 'Cancelled')
                                                 Cancelled
                                             @elseif ($d->projso_status == 'Done')
                                                 Done
                                             @else
                                                 Already Taken
                                             @endif --}}
                                         @endif
                                     @endif
                                 </div>
                             </td>
                         </tr>
                     @endforeach
                 @endif
             </tbody>
         </table>
         <nav>
             {{ $work_order->links('pagination::bootstrap-5') }}
         </nav>
     </div>
 </div>
