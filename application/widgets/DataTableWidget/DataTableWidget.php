<?php
/**
 * User: Nahid Hossain
 * Email: mail@akmnahid.com
 * Phone: +880 172 7456 280
 * Date: 6/3/2015
 * Time: 2:58 PM
 */
require_once APPPATH.'/core/Easol_BaseWidget.php';

class DataTableWidget extends Easol_BaseWidget {

    /**
     * Run the widget functionality
     */
    public $query;

    public $pagination=null;

    /*
     *  ['column'    =>  'name',
     *  'type'      =>  'text',
     *  'title'      =>  'title'
     *  ]
     */
    public $columns= [

    ];

    public $filter=null;


    public function run()
    {
        if($this->filter!=null && array_key_exists('filter',$_GET)){ /*@filter*/
            $queryAddition=' ';
            foreach($this->filter['fields'] as $key => $field){
                if($field['type']=='dropdown' && $this->input->get('filter['.$key.']')!=""){
                    $queryAddition.=" AND ".$field['searchColumn']."=".$this->db->escape($this->input->get('filter['.$key.']'))." ";

                }
            }

            $this->query=str_replace('/*@filter*/',$queryAddition,$this->query);

            //die($this->query);


        }
        if($this->pagination!=null){
            $this->query.='  OFFSET ? ROWS FETCH NEXT ? ROWS ONLY';
            $dbQuery= $this->db->query($this->query,[abs($this->pagination['currentPage']-1)*$this->pagination['pageSize'],$this->pagination['pageSize']]);

        }
        else
            $dbQuery= $this->db->query($this->query);

        $this->render("view",[
            'query'     =>  $dbQuery,
            'columns'   =>  $this->columns,
            'pagination'    =>  $this->pagination,
            'filter'    =>  $this->filter
        ]);
    }
}