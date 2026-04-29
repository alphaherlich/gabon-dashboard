package com.gabon.dashboard.model;

import jakarta.persistence.*;
import lombok.*;

@Entity
@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Table(name = "stat_indicator")
public class StatIndicator {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    private String category; // ECONOMY, HEALTH, EDUCATION

    private String title;    // ex: coût de la vie

    @Column(name = "stat_value")
    private Double value;

    @Column(name = "stat_year")
    private Integer year;

    private String region;   // Libreville, etc

    private String insight;  // phrase humaine (important 🔥)
}