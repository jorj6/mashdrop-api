<?php //-->

namespace Services;

use Services\Transaction as T;
use Services\Campaign\Post;

/**
 * Service Balance
 * business logic of this class object
 *
 * @category   service
 * @author     javincX
 */
class Balance
{
    /* Constants
    --------------------------------------------*/
    /* Public Properties
    --------------------------------------------*/
    /* Protected Properties
    --------------------------------------------*/
    /* Private Properties
    --------------------------------------------*/
    /* Public Methods
    --------------------------------------------*/
    public static function find($options)
    {
        // predefined filters
        $options['fields'] = array('id', 'user_id', 'campaign_id');

        // get campaign posts will separate user
        $posts = Post::find($options);
        $data = array();
        foreach($posts as $key => $post) {
            $data[$post['user_id']]['user_id'] = $post['user_id'];
            $data[$post['user_id']]['total_revenue'] += $post['meta']['revenue'];
        }

        // get total payouts by user
        foreach($data as $userId => $row) {
            $payouts = T::find(array(
                'fields' => array('amount'),
                'filters' => array(
                    'type' => T::PAYOUT_TYPE,
                    'user_id' => $userId)));

            $totalPayout = 0;
            foreach($payouts as $payout) {
                $totalPayout += (float) $payout['amount'];
            }

            $data[$userId]['total_payout'] = $totalPayout;
            $data[$userId]['total_revenue'] = round($row['total_revenue'], 2);
            $data[$userId]['total_balance'] = round($row['total_revenue']
                - $totalPayout, 2);
        }

        return $data;
    }

    public static function get($options)
    {
        return current(self::find($options));
    }

    /* Protected Methods
    --------------------------------------------*/
    /* Private Methods
    --------------------------------------------*/
}
