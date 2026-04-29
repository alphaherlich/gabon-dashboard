package com.gabon.dashboard.repository;

import com.gabon.dashboard.model.StatIndicator;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface StatIndicatorRepository extends JpaRepository<StatIndicator, Long> {

    List<StatIndicator> findByCategory(String category);

    List<StatIndicator> findByYear(int year);

    List<StatIndicator> findByRegion(String region);
}