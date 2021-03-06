<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (env('APP_ENV', 'production') === 'production') {
    URL::forceScheme('https');
}

Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::guest()) {
        return view('welcome');
    } else {
        $role = Auth::user()->role_id;
        switch ($role) {
            case "1":
                return redirect(route('participant.home'));

            case "2":
                return redirect(route('oc.home'));

            case "3":
            return redirect(route('checkin.home'));

            case "4":
                return redirect(route('voting.home'));

            default:
                return redirect(route('home'));
        }
    }
})->name('home');


//CAS
Route::get('/login', 'UnauthenticatedController@login')->name('cas.login');
Route::get('/logout', 'UnauthenticatedController@logout')->name('cas.logout');

//Participants
Route::get('/account', 'ParticipantController@index')->name('participant.home');
Route::get('/account/profile', 'ParticipantController@showProfile')->name('profile');
Route::get('/nr/delegation', 'ParticipantController@delegation')->name('participant.delegation');
Route::get('/account/payment', 'ParticipantController@payment')->name('participant.payment');
Route::post('/account/validateCard', 'ParticipantController@validateCard')->name('participant.validateCard');
Route::get('/account/charge', 'ParticipantController@charge')->name('participant.charge');
Route::get('/account/deposit', 'ParticipantController@deposit')->name('participant.deposit');
Route::post('/account/parseToken', 'ParticipantController@parseToken')->name('participant.parseToken');
Route::get('/account/chargeDeposit', 'ParticipantController@chargeDeposit')->name('participant.deposit.charge');
Route::get('/account/proof', 'ParticipantController@generateProof')->name('participant.generateProof');
Route::get('/account/certificate', 'ParticipantController@certificate')->name('participant.certificate');
Route::get('/account/logout', 'ParticipantController@logout')->name('participant.logout');

//OC
Route::get('/oc', 'OCController@index')->name('oc.home');
Route::get('/oc/final', 'OCController@final')->name('oc.final');
Route::get('/oc/approved', 'OCController@approved')->name('oc.approved');
Route::get('/oc/approved/sync', 'OCController@approvedSync')->name('oc.approved.sync');
Route::get('/oc/namechanges', 'OCController@namechanges')->name('oc.namechanges');
Route::get('/oc/namechanges/sync', 'OCController@namechangesSync')->name('oc.namechanges.sync');
Route::get('/oc/namechanges/match/{user}', 'OCController@namechangesMatchShow')->name('oc.namechanges.match.show');
Route::post('/oc/namechanges/match', 'OCController@namechangesMatch')->name('oc.namechanges.match');
Route::get('/oc/cancelled', 'OCController@cancelled')->name('oc.cancelled');
Route::get('/oc/cancelled/sync', 'OCController@cancelledSync')->name('oc.cancelled.sync');
Route::get('/oc/cashflow/all', 'OCController@cashflow')->name('oc.cashflow');
Route::get('/oc/cashflow/card', 'OCController@cashflowCard')->name('oc.cashflow.card');
Route::get('/oc/cashflow/bank', 'OCController@cashflowBank')->name('oc.cashflow.bank');
Route::get('/oc/cashflow/debts', 'OCController@cashflowDebts')->name('oc.cashflow.debts');
Route::get('/oc/cashflow/deposits', 'OCController@cashflowDeposits')->name('oc.cashflow.deposits');
Route::put('/oc/cashflow/deposits/acquire/{transaction}', 'OCController@acquireDeposit')->name('oc.deposits.acquire');
Route::delete('/oc/cashflow/deposits/refund/{transaction}', 'OCController@refundDeposit')->name('oc.deposits.refund');
Route::get('/oc/cashflow/bank/sync', 'OCController@cashflowBankSync')->name('oc.cashflow.bank.sync');
Route::get('/oc/transaction/{transaction}', 'OCController@transaction')->name('oc.transaction.show');
Route::get('/oc/transaction/approve/{transaction}', 'OCController@approveTransactionShow')->name('oc.transaction.approve.show');
Route::put('/oc/transaction/approve', 'OCController@approveTransaction')->name('oc.transaction.approve');
Route::delete('/oc/transaction/{transaction}', 'OCController@deleteTransaction')->name('oc.transaction.delete');
Route::get('/oc/debt/edit/{transaction}', 'OCController@editDebtShow')->name('oc.debt.edit.show');
Route::put('/oc/debt/edit', 'OCController@editDebt')->name('oc.debt.edit');
Route::delete('oc/debt/{transaction}','OCController@deleteDebt')->name('oc.debt.delete');
Route::get('/oc/user/{user}', 'OCController@user')->name('oc.user.show');
Route::put('/oc/comments/edit', 'OCController@editUserComments')->name('oc.comments.edit');
Route::get('/oc/crud/hotels', 'OCController@crudHotels')->name('oc.crud.hotels');
Route::get('/oc/crud/hotels/edit/{hotel}', 'OCController@showEditHotel')->name('oc.crud.hotels.edit.show');
Route::post('/oc/crud/hotels/doEdit', 'OCController@editHotel')->name('oc.crud.hotels.edit');
Route::get('/oc/crud/hotels/delete/{hotel}', 'OCController@deleteHotel')->name('oc.crud.hotels.delete');
Route::get('/oc/crud/rooms', 'OCController@crudRooms')->name('oc.crud.rooms');
Route::get('/oc/crud/rooms/edit/{room}', 'OCController@showEditRoom')->name('oc.crud.rooms.edit.show');
Route::post('/oc/crud/rooms/doEdit', 'OCController@editRoom')->name('oc.crud.rooms.edit');
Route::get('/oc/crud/rooms/delete/{room}', 'OCController@deleteRoom')->name('oc.crud.rooms.delete');
Route::get('/oc/invitations', 'OCController@showInvitations')->name('oc.invitations.show');
Route::get('/oc/invitations/sync', 'OCController@invitationsSync')->name('oc.invitations.sync');
Route::get('/oc/invitation/{user}/send', 'OCController@invitationSend')->name('oc.invitation.send');
Route::get('/oc/logout', 'OCController@logout')->name('oc.logout');
Route::get('/oc/checkin/deposits/requests', 'OCController@checkinDepositRequests')->name('oc.checkin.depositRequests');
Route::get('/oc/checkin/deposit{transaction}/approve', 'OCController@checkinDepositRequestApprove')->name('oc.checkin.depositRequest.approve');
Route::get('/oc/checkin/deposit{transaction}/delete', 'OCController@checkinDepositRequestDelete')->name('oc.checkin.depositRequest.delete');
Route::get('/oc/checkin/checkiners', 'OCController@checkiners')->name('oc.checkin.checkiners');


//Rooming import
Route::get('/oc/import/rooming/show', 'OCController@importRoomingShow')->name('oc.import.rooming.show');
Route::post('/oc/import/rooming', 'OCController@importRooming')->name('oc.import.rooming');
Route::get('/oc/import/esncard/show', 'OCController@importEsncardShow')->name('oc.import.esncard.show');
Route::post('/oc/import/esncard', 'OCController@importEsncard')->name('oc.import.esncard');
Route::get('/oc/import/delegations/show', 'OCController@importVotingDelegationsShow')->name('oc.import.delegations.show');
Route::post('/oc/import/delegations', 'OCController@importVotingDelegations')->name('oc.import.delegations');

//Checkin
Route::get('/checkin', 'CheckinController@index')->name('checkin.home');
Route::get('/checkin/hotel/{hotel}', 'CheckinController@hotel')->name('checkin.hotel');
Route::get('/checkin/hotel/{hotel}/checkin/validate/{user}', 'CheckinController@validation')->name('checkin.validate');
Route::post('/checkin/hotel/checkin', 'CheckinController@checkin')->name('checkin.checkin');
Route::get('/checkin/funds', 'CheckinController@funds')->name('checkin.funds');
Route::get('/checkin/funds/request/show', 'CheckinController@createDepositPickupRequestShow')->name('checkin.funds.createRequest.show');
Route::post('/checkin/funds/request', 'CheckinController@createDepositPickupRequest')->name('checkin.funds.createRequest');
Route::get('/checkin/logout', 'CheckinController@logout')->name('checkin.logout');

//Checkout
Route::get('/checkout', 'CheckoutController@index')->name('checkout.home');
Route::get('/checkout/hotel/{hotel}', 'CheckoutController@hotel')->name('checkout.hotel');
Route::get('/checkout/hotel/{hotel}/checkout/validate/{user}', 'CheckoutController@validation')->name('checkout.validate');
Route::post('/checkout/hotel/checkout', 'CheckoutController@checkout')->name('checkout.checkout');
Route::get('/checkout/logout', 'CheckoutController@logout')->name('checkout.logout');

//Voting devices
Route::get('/voting', 'VotingDistributionController@index')->name('voting.home');
Route::get('/voting/device/user/validate/{delegation_id}', 'VotingDistributionController@validation')->name('voting.validate');
Route::get('/voting/device/user/{delegation_id}', 'VotingDistributionController@device')->name('voting.device');
Route::get('/voting/device/delegation/{round_id}', 'VotingDistributionController@round')->name('voting.round');
Route::get('/voting/logout', 'VotingDistributionController@logout')->name('voting.logout');


Route::get('/voting/device/user/validate/return/{delegation_id}', 'VotingDistributionController@returnValidation')->name('voting.return.validate');
Route::get('/voting/device/user/return/{delegation_id}', 'VotingDistributionController@returnDevice')->name('voting.return.device');
Route::get('/voting/device/delegation/return/{round_id}', 'VotingDistributionController@returnRound')->name('voting.return.round');

//Misc
Route::get('/terms', 'MiscController@terms')->name('terms');

//Test
Route::get('/oc/test', 'OCController@test')->name('oc.test');

