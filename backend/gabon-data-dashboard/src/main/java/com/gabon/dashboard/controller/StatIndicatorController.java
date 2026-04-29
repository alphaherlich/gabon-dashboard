package com.gabon.dashboard.controller;

import com.gabon.dashboard.model.StatIndicator;
import com.gabon.dashboard.service.StatIndicatorService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/stats")
@CrossOrigin("*")
public class StatIndicatorController {

    @Autowired
    private StatIndicatorService service;

    @PostMapping
    public StatIndicator add(@RequestBody StatIndicator data) {
        return service.save(data);
    }

    @GetMapping
    public List<StatIndicator> getAll() {
        return service.getAll();
    }

    @GetMapping("/category/{cat}")
    public List<StatIndicator> byCategory(@PathVariable String cat) {
        return service.getByCategory(cat);
    }

    @GetMapping("/year/{year}")
    public List<StatIndicator> byYear(@PathVariable int year) {
        return service.getByYear(year);
    }

    @GetMapping("/region/{region}")
    public List<StatIndicator> byRegion(@PathVariable String region) {
        return service.getByRegion(region);
    }
}