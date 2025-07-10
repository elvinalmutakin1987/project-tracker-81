  <ul class="nav nav-pills">
      <li class="nav-item">
          <a class="nav-link active" aria-current="page"
              href="{{ route('task_board.index', ['assignee' => 'pre-sales']) }}">Pre Sales
              @if ($project_survey->where('projsur_status', '!=', 'Done')->count() > 0)
                  <span
                      class="badge text-bg-warning rounded-pill">{{ $project_survey->where('projsur_status', '!=', 'Done')->count() }}</span>
              @endif
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link active" aria-current="page"
              href="{{ route('task_board.index', ['assignee' => 'sales-admin']) }}">Sales
              Admin
              @if ($project_offer->where('projoff_status', '!=', 'Done')->count() > 0)
                  <span
                      class="badge text-bg-warning rounded-pill">{{ $project_offer->where('projoff_status', '!=', 'Done')->count() }}</span>
              @endif
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('task_board.index', ['assignee' => 'operation']) }}">Operation
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('task_board.index', ['finance-accounting' => 'operation']) }}">Finance
              & Accounting</a>
      </li>
  </ul>
