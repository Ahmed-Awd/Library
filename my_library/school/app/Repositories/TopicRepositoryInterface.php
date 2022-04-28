<?php

namespace App\Repositories;

interface TopicRepositoryInterface
{
    public function get($withSubTopics);

    public function show($topic);

    public function getParentTopic($topic);

    public function getSubTopics($topic);

    public function delete($topic);

    public function store($data);

    public function update($topic, $data);

    public function getContents($topic);
}
