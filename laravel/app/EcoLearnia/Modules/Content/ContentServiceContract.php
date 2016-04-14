<?php

namespace App\EcoLearnia\Modules\Content;

interface ContentServiceContract
{
    /**
     *
     */
    public function name();


    // Resource Access Operations {{

    /**
     * Add
     *
     * @param Object  $resource - The resource (record) to add
     * @param Object  $options  - Any options for add operation
     * @return Model  - Upon success, return the added model
     */
    public function add($resource, $options);

    /**
     * query
     *
     * @param Object  $criteria - The criteria for the query
     * @param Object  $options  - Any options for query operation
     * @return Array.<Model>  - Upon success, return the models
     */
    public function query($criteria, $options);

    /**
     * query
     *
     * @param Object  $criteria - The criteria for the query
     * @param Object  $options  - Any options for query operation
     * @return number  - Upon success, return count satisfying the criteria
     */
    public function count($criteria, $options);

    /**
     * Find
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for find operation
     * @return Model  - Upon success the model returned
     */
    public function find($criteria, $options);

    /**
     * Find by PK
     *
     * @param mixed  $pk - The primary key of the resource to find
     * @param object  $options  - Any options for find operation
     * @return Model  - Upon success the model returned
     */
    public function findByPK($pk, $options);

    /**
     * Update
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $resource  - The resource (record) to update
     * @param object  $options  - Any options for update operation
     * @return Model  - Upon success the model returned
     */
    public function update($criteria, $resource, $options);

    /**
     * Remove
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function remove($criteria, $options);

    /**
     * Remove
     *
     * @param mixed  $pk - The primary key of the resource to remove
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function removeByPK($pk, $options);

    // }} Resource Access Operations
}
