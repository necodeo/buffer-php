<?php 

namespace Buffer;

class Reader
{
    private string $data = "";
    public int $offset = 0;
    public int $left;

    public function increase(int $increase = 0): void {
        $this->offset += $increase;
        $this->left -= $increase;
    }

    public function __construct(string $data) {
        $this->data = $data;
        $this->left = strlen($data);
    }

    public function char(): string {
        $data = substr($this->data, $this->offset, 1);
        $this->increase(1);
        return $data;
    }

    public function int8(): int {
        $data = unpack("c", $this->data, $this->offset)[1];
        $this->increase(1);
        return $data;
    }

    public function int16(): int {
        $data = unpack("v", $this->data, $this->offset)[1];
        $this->increase(2);
        return $data;
    }

    public function int32(): int {
        $data = unpack("l", $this->data, $this->offset)[1];
        $this->increase(4);
        return $data;
    }

    public function uint32(): int {
        $data = unpack("V", $this->data, $this->offset)[1];
        $this->increase(4);
        return $data;
    }

    public function int64(): int {
        $data = unpack("q", $this->data, $this->offset)[1];
        $this->increase(8);
        return $data;
    }

    public function float32(): float {
        $data = unpack("f", $this->data, $this->offset)[1];
        $this->increase(4);
        return $data;
    }

    public function bytes(int $length = 0): string {
        $data = substr($this->data, $this->offset, $length);
        $this->increase($length);
        return $data;
    }

    public function end(): string {
        $length = strlen($this->data) - $this->offset;
        $data = substr($this->data, $this->offset, $length);
        $this->increase($length);
        return $data;
    }

    public function string(): string { // unpack('a', )?
        $position = strpos($this->data, "\0", $this->offset);
        if ($position === false) {
            $position = strlen($this->data);
        }
        $length = $position - $this->offset;
        $data = substr($this->data, $this->offset, $length);
        $this->increase($length + 1);
        return $data;
    }
}