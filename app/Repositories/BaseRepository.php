<?php
/**
 * Created by Mahbubul Alam
 * User: Masakh IT
 * Date: 4/9/21
 * Time: 7:53 PM
 */


namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return Model
     */
    public function find($id, $columns = ['*']) :? Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param array $where
     * @param string[] $columns
     * @return Model|null
     */
    public function findWhere(array $where, $columns = ['*']) :? Model
    {
        $this->applyConditions($where);

        return $this->model->first($columns);
    }

    /**
     * @param $field
     * @param null $value
     * @param string[] $columns
     * @return Model|null
     */
    public function findByField($field, $value = null, $columns = ['*']) :? Model
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }

    /**
     * @param $field
     * @param array $values
     * @param string[] $columns
     * @return Model|null
     */
    public function findWhereIn($field, array $values, $columns = ['*']) :? Model
    {
        return $this->model->whereIn($field, $values)->first($columns);
    }

    /**
     * @param $field
     * @param array $values
     * @param string[] $columns
     * @return Model|null
     */
    public function findWhereBetween($field, array $values, $columns = ['*']) :? Model
    {
        return $this->model->whereBetween($field, $values)->first($columns);
    }

    /**
     * @param $field
     * @param array $values
     * @param string[] $columns
     * @return Model|null
     */
    public function findWhereNotIn($field, array $values, $columns = ['*']) :? Model
    {
        return $this->model->whereNotIn($field, $values)->first($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function bulkCreate(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param $field
     * @param null $value
     * @param string[] $columns
     * @return Collection|null
     */
    public function getByField($field, $value = null, $columns = ['*']) :? Collection
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    /**
     * @param array $where
     * @param string[] $columns
     * @return Collection|null
     */
    public function getWhere(array $where, $columns = ['*']) :? Collection
    {
        $this->applyConditions($where);

        return $this->model->get($columns);
    }

    /**
     * @param $field
     * @param array $values
     * @param string[] $columns
     * @return Collection|null
     */
    public function getWhereIn($field, array $values, $columns = ['*']) :? Collection
    {
        return $this->model->whereIn($field, $values)->get($columns);
    }

    /**
     * @param $field
     * @param array $values
     * @param string[] $columns
     * @return Collection|null
     */
    public function getWhereNotIn($field, array $values, $columns = ['*']) :? Collection
    {
        return $this->model->whereNotIn($field, $values)->get($columns);
    }

    public function getWhereBetween($field, array $values, $columns = ['*'])
    {
        return $this->model->whereBetween($field, $values)->get($columns);
    }

    /**
     * @param string[] $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @param $column
     * @param null $key
     * @return mixed
     */
    public function pluck($column, $key = null)
    {
        return $this->model->pluck($column, $key);
    }

    /**
     * @param array $where
     * @param string $columns
     * @return mixed
     */
    public function count(array $where = [], $columns = '*')
    {
        if ($where) {
            $this->applyConditions($where);
        }

        return $this->model->count($columns);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        /*return tap($this->model->find(1), function ($user) use ($attributes) {
            return $user->update($attributes);
        })->fresh();*/
        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function updateAndGetData(array $attributes, $id)
    {
        return tap($this->model->find($id), function ($user) use ($attributes) {
            return $user->update($attributes);
        })->fresh();
//        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * @param array $where
     * @param array $values
     * @return mixed
     */
    public function updatewhere(array $where, array $values = [])
    {
        return $this->model->where($where)->update($values);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * @param array $where
     * @return bool|null
     * @throws \Exception
     */
    public function deleteWhere(array $where) : bool
    {
        $this->applyConditions($where);

        return $this->model->delete();
    }

    /**
     * @param array $where
     * @return bool|null
     * @throws \Exception
     */
    public function deleteByField(string $field, $value) : bool
    {
        return $this->model->where($field,$value)->delete();
    }

    /**
     * @param $column
     * @param array $where
     * @return mixed
     */
    public function deleteWhereIn($column, array $where) : bool
    {
        return $this->model->whereIn($column, $where)->delete();
    }

    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}
