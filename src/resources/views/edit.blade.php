@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<button type="button" onClick="history.back()" class="backbtn">
  <
</button>
<form action="{{ url('/update/' . $reservation->id) }}" method="post">
        @csrf
        <ul class="cardlist">
            <li>
                <input name="date" type="date" id="date" min="{{ date('Y-m-d') }}"  value="{{ old('date', $reservation->date) }}" class="cardlist_form cardlist_input" onchange="updateReservationDetails()" />
            </li>
            <li>
                <select name="time" id="time" class="cardlist_form cardlist_select" onchange="updateReservationDetails()">
                    <option value="" selected="">未選択</option>
                    @for($i = 7; $i <= 20; $i++)
                    @for($j = 0; $j <= 5; $j++)
                      <option value="{{$i}}:{{$j}}0">
                        {{$i}}:{{$j}}0
                      </option>
                    @endfor
                    @endfor
                </select>
            </li>
            <li>
                <select name="number" id="peopleInput" class="cardlist_form cardlist_select" onchange="updateReservationDetails()">
                    @for($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ old('number', $reservation->number) == $i ? 'selected' : '' }}>
                          {{ $i }}人
                        </option>
                    @endfor
                </select>
            </li>
        </ul>
        <!-- インプットをここに表示 -->
        <div id="reservationDetails" class="reservationdetail">
            <table>
                <tr>
                    <th class="detailtitle">Shop</th>
                    <td>{{ $store->store }}</td>
                </tr>
                <tr>
                    <th class="detailtitle">Date</th>
                    <td><span id="selectedDate"></span></td>
                </tr>
                <tr>
                    <th class="detailtitle">Time</th>
                    <td><span id="selectedTime"></span></td>
                </tr>
                <tr>
                    <th class="detailtitle">Number</th>
                    <td><span id="selectedPeople"></span></td>
                </tr>
            </table>
        </div>

        <div class="error">
            @if (count($errors) > 0)
                <p>入力に問題があります</p>  
            @endif

            <div class="error_content">
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </div>
        </div>

        <button type="submit" class="reservationbtn">変更する</button>
    </form>


    <script>
    // 現在の日付を取得し、最小日付を設定
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').setAttribute('min', today);

    // 予約詳細を更新する関数
    function updateReservationDetails() {
        // 各入力要素から値を取得
        const dateInput = document.getElementById('date').value;
        const timeInput = document.getElementById('time').value;
        const peopleInput = document.getElementById('peopleInput').value;

        // 予約内容を表示する要素に値をセット
        document.getElementById('selectedDate').textContent = dateInput || '未選択';
        document.getElementById('selectedTime').textContent = timeInput || '未選択';
        document.getElementById('selectedPeople').textContent = peopleInput ? `${peopleInput}人` : '未選択';
        
        // 時間オプションを更新
        updateTimeOptions(dateInput);
    }

    // 現在以前の時間を選択できないようオプションを更新する関数
    function updateTimeOptions(selectedDate) {
        const timeSelect = document.getElementById('time');
        const now = new Date();
        const currentHour = now.getHours();
        const currentMinute = now.getMinutes();

        // 時間オプションを全て有効化
        for (let i = 0; i < timeSelect.options.length; i++) {
            timeSelect.options[i].disabled = false;
        }

        // 現在の日付が選択された場合、現在時刻より前の時間オプションを無効化
        if (selectedDate === today) {
            for (let i = 0; i < timeSelect.options.length; i++) {
                const optionValue = timeSelect.options[i].value;
                const [hour, minute] = optionValue.split(':').map(Number);

                if (hour < currentHour || (hour === currentHour && minute < currentMinute)) {
                    timeSelect.options[i].disabled = true;
                }
            }
        }
    }

    // ページが読み込まれたときに初期状態を設定
    window.onload = () => {
        updateReservationDetails();
        
        // 入力要素にイベントリスナーを追加
        document.getElementById('date').addEventListener('input', updateReservationDetails);
        document.getElementById('time').addEventListener('change', updateReservationDetails);
        document.getElementById('peopleInput').addEventListener('change', updateReservationDetails);
    };
</script>



@endsection