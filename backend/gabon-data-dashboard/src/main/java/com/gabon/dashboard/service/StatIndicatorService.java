package com.gabon.dashboard.service;

import com.gabon.dashboard.model.StatIndicator;
import com.gabon.dashboard.repository.StatIndicatorRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class StatIndicatorService {

    @Autowired
    private StatIndicatorRepository repository;

    public StatIndicator save(StatIndicator data) {
        return repository.save(data);
    }

    public List<StatIndicator> getAll() {
        return repository.findAll();
    }

    public List<StatIndicator> getByCategory(String category) {
        return repository.findByCategory(category);
    }

    public List<StatIndicator> getByYear(int year) {
        return repository.findByYear(year);
    }

    public List<StatIndicator> getByRegion(String region) {
        return repository.findByRegion(region);
    }
}