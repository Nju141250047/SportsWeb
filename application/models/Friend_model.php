<?php

class Friend_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    function get_friends($userid)
    {
        $this->db->select('user.userid, user.username, friend_relation.group');
        $this->db->from('user');
        $this->db->join('friend_relation', 'user.userid = friend_relation.friendid');
        $this->db->where('friend_relation.userid', $userid);
        $query = $this->db->get();
        return $query->result();
    }

    function add_friend($userid, $friendid, $group)
    {
        $data = array(
            'userid' => $userid,
            'friendid' => $friendid,
            'group' => $group
        );
        $this->db->insert('friend_relation', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function update_friend($userid, $friendid, $group)
    {
        $data = array(
            'group' => $group,
        );
        $this->db->where(array(
            'userid' => $userid,
            'friendid' => $friendid
        ));
        $this->db->update('friend_relation', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function delete_friend($userid, $friendid)
    {
        $data = array(
            'userid' => $userid,
            'friendid' => $friendid
        );
        $this->db->delete('friend_relation', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    function get_friend_updates($userid)
    {
        $this->db->select('user.username, count(*) as news_num, sum(photo_count) as photo_num');
        $this->db->from('friend_relation');
        $this->db->join('user', 'user.userid = friend_relation.friendid');
        $this->db->join('friend_news', 'friend_news.ownerid = friend_relation.friendid');
        $this->db->where('friend_relation.userid', $userid);
        $this->db->group_by("friend_relation.friendid");
        $query = $this->db->get();
        return $query->result();
    }

    function get_groups($userid){
        $this->db->select('friend_relation.group as group, count(*) as num');
        $this->db->from('user');
        $this->db->join('friend_relation', 'user.userid = friend_relation.friendid');
        $this->db->where('friend_relation.userid', $userid);
        $this->db->group_by("friend_relation.group");
        $query = $this->db->get();
        return $query->result();
    }
}